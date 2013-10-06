<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 10/3/13
 * Time: 11:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\ContestNominationsBundle\Validator;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Rodgermd\ContestNominationsBundle\Entity\ContestVote;
use Rodgermd\ContestNominationsBundle\Entity\ContestVoteRepository;
use Rodgermd\ContestNominationsBundle\Manager\ContestManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasVoteLimitValidator extends ConstraintValidator {

  /** @var ContestManager  */
  protected $manager;

  public function __construct(ContestManager $manager)
  {
    $this->manager = $manager;
  }

  /**
   * Checks if the passed value is valid.
   *
   * @param mixed $value      The value that should be validated
   * @param Constraint $constraint The constraint for the validation
   *
   * @api
   */
  public function validate($value, Constraint $constraint)
  {
    /** @var ContestVote $value */
    $contest = $value->getContestMember()->getContest();

    if (!$this->manager->isVoteAllowed($contest)) $this->context->addViolation($constraint->message);
  }

}