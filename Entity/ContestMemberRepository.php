<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ContestMemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContestMemberRepository extends EntityRepository
{
  public function getExtendedMembersData(ContestNomination $nomination, $ip)
  {
    $qb = $this->createQueryBuilder('cm')
      ->select('cm, ci')
      ->leftJoin('cm.images', 'ci')
      ->leftJoin('cm.votes', 'cv', 'WITH', 'cv.ip = :ip')
      ->where('cm.contest_nomination = :nomination')
      ->setParameter('nomination', $nomination->getId())
      ->setParameter('ip', $ip)
      ->orderBy('cm.votes_number', 'desc');

    $members = $qb->getQuery()->getResult();

    if (!count($members)) return $members; // return if no members

    // update places

    /** @var ContestMember $prev */
    $prev = array_shift($members);
    $prev->setPlace(1);

    $result = array($prev);

    while ($member = array_shift($members))
    {
      /** @var ContestMember $member */
      $prev_place = $prev->getPlace();
      $member->setPlace( ($member->getVotesNumber() < $prev->getVotesNumber()) ? $prev_place + 1 : $prev_place);
      $prev = $member;
      $result[] = $member;
    }

    return $result;
  }

}
