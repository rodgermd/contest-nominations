<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 04.10.13
 * Time: 17:06
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\ContestNominationsBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Rodgermd\ContestNominationsBundle\Entity\ContestNomination;
use Rodgermd\SfToolsBundle\Twig\ImagesExtension;
use Rodgermd\ContestNominationsBundle\Entity\Contest;
use Rodgermd\ContestNominationsBundle\Entity\ContestMember;
use Rodgermd\ContestNominationsBundle\Entity\ContestMemberRepository;
use Rodgermd\ContestNominationsBundle\Entity\ContestVote;
use Rodgermd\ContestNominationsBundle\Entity\ContestVoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ContestManager
 *
 * @package Rodgermd\ContestNominationsBundle\Manager
 */
class ContestManager
{
    /** @var Registry $registry */
    protected $doctrine;
    /** @var ContestMemberRepository */
    protected $contest_member_repository;
    /** @var ContestVoteRepository */
    protected $contest_vote_repository;
    /** @var Request $request */
    protected $request;
    /** @var ImagesExtension $images_helper */
    protected $images_helper;
    /** @var ValidatorInterface $validator */
    protected $validator;
    /** @var int */
    protected $vote_limit;

    public function __construct(Registry $doctrine, Request $request, ImagesExtension $images_helper, ValidatorInterface $validator, $vote_limit)
    {
        $this->doctrine                  = $doctrine;
        $this->contest_member_repository = $doctrine->getRepository('Rodgermd\ContestNominationsBundle\Entity\ContestMember');
        $this->contest_vote_repository   = $doctrine->getRepository('Rodgermd\ContestNominationsBundle\Entity\ContestVote');
        $this->request                   = $request;
        $this->images_helper             = $images_helper;
        $this->validator                 = $validator;
        $this->vote_limit                = $vote_limit;
    }

    /**
     * Checks if current user is allowed to vote in contest
     *
     * @param ContestNomination $contest_nomination
     *
     * @return bool
     */
    public function isVoteAllowed(ContestNomination $contest_nomination)
    {
        $last_vote = $this->getLastContestVote($contest_nomination);

        return $this->checkVoteAllowsToVoteNow($last_vote);
    }

    /**
     * Gets last content vote
     *
     * @param ContestNomination $contest_nomination
     *
     * @return null|ContestVote
     */
    public function getLastContestVote(ContestNomination $contest_nomination)
    {
        return $this->contest_vote_repository->getLastContestVote($contest_nomination, $this->request->getClientIp());
    }

    /**
     * Check if last vote allows to vote again
     *
     * @param ContestVote $vote
     *
     * @return bool
     */
    public function checkVoteAllowsToVoteNow(ContestVote $vote = null)
    {
        if (!$vote) {
            return true;
        }
        $date = new \DateTime(sprintf('-%d minutes', (int)$this->vote_limit));

        return $vote->getCreatedAt() <= $date;
    }

    /**
     * Gets response for contest members
     *
     * @param ContestNomination $nomination
     *
     * @return Response
     */
    public function getNominationMembersResponse(ContestNomination $nomination)
    {
        $result  = array();
        $members = array();
        foreach ($this->contest_member_repository->getExtendedMembersData($nomination, $this->request->getClientIp()) as $contest_member) {
            /** @var ContestMember $contest_member */
            $images = array();
            foreach ($contest_member->getImages() as $image) {
                $images[] = $this->images_helper->uploaded_image_source($image, 'contest_member_popup_images', 'image');
            }
            $data                  = $contest_member->toArray();
            $data['profile_image'] = $this->images_helper->uploaded_image_source($contest_member, 'contest_member_popup_avatar', 'image');
            $data['list_image']    = $this->images_helper->uploaded_image_source($contest_member, 'contest_member_preview', 'image');
            $data['member']        = $contest_member->getMember()->toArray();
            $data['images']        = $images;
            $data['voted']         = $contest_member->getVotes()->count();
            $members[]             = $data;
        }

        $result['members']    = $members;
        $result['allow_vote'] = $this->isVoteAllowed($nomination);

        return $this->json_response($result);
    }

    /**
     * Stores vote for contest member
     *
     * @param ContestMember $contest_member
     *
     * @return Response
     * @throws \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function vote(ContestMember $contest_member)
    {
        $vote = new ContestVote();
        $vote->setContestMember($contest_member);
        $vote->setIp($this->request->getClientIp());

        $errors = $this->validator->validate($vote);
        if ($errors->count()) {
            throw new ValidatorException((string)$errors);
        }

        $em = $this->doctrine->getManager();
        $em->persist($vote);
        $em->flush();

        $em->refresh($contest_member);
        $contest_member->setVotesNumber($contest_member->getVotes()->count());
        $em->persist($vote);
        $em->flush();

        return new Response('', 201);
    }

    /**
     * Prepares Response of type json with data provided
     *
     * @param array $data
     * @param int   $status
     *
     * @return Response
     */
    protected function json_response(array $data, $status = 200)
    {
        $response = new Response();
        $response->headers->add(array('content-type' => 'application/json'));
        $response->setContent(json_encode($data));
        $response->setStatusCode($status);

        return $response;
    }
}