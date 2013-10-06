<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Rodgermd\ContestNominationsBundle\Validator\HasVoteLimit;

/**
 * ContestVote
 *
 * @ORM\Table(name="contest__votes")
 * @ORM\Entity(repositoryClass="Rodgermd\ContestNominationsBundle\Entity\ContestVoteRepository")
 * @HasVoteLimit
 */
class ContestVote
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="ip", type="string", length=15)
   * @Assert\NotNull(message="IP address is required")
   */
  private $ip;

  /**
   * @var ContestMember
   * @ORM\ManyToOne(targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestMember", inversedBy="votes")
   * @Assert\NotNull(message="Member is required")
   */
  private $contest_member;

  /**
   * @var \DateTime
   * @ORM\Column(name="created_at", type="datetime")
   * @Gedmo\Timestampable(on="create")
   */
  private $created_at;


  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set ip
   *
   * @param string $ip
   * @return ContestVote
   */
  public function setIp($ip)
  {
    $this->ip = $ip;

    return $this;
  }

  /**
   * Get ip
   *
   * @return string
   */
  public function getIp()
  {
    return $this->ip;
  }

  /**
   * Gets ContestMember
   * @return ContestMember
   */
  public function getContestMember()
  {
    return $this->contest_member;
  }

  /**
   * Sets ContestMember
   * @param ContestMember $contest_member
   * @return $this
   */
  public function setContestMember(ContestMember $contest_member)
  {
    $this->contest_member = $contest_member;

    return $this;
  }

  /**
   * Get createdAt
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }
}
