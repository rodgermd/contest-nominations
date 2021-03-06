<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContestRepository extends EntityRepository
{
  /**
   * Gets active contests
   * @return array
   */
  public function getActiveContests()
  {
    return $this->createQueryBuilder('c')
      ->where('c.is_published = true AND c.end_date >= :now')->setParameter('now', new \DateTime())
      ->getQuery()->getResult();
  }
}
