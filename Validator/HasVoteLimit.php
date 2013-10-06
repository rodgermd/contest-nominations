<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 10/3/13
 * Time: 11:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\ContestNominationsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class HasVoteLimit
 * @package Rodgermd\ContestNominationsBundle\Validator
 * @Annotation
 */
class HasVoteLimit extends Constraint
{
  public $message = "Votes limit exceeded";

  public function getTargets()
  {
    return self::CLASS_CONSTRAINT;
  }

  public function validatedBy()
  {
    return 'has_vote_limit';
  }
}