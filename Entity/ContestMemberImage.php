<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ContestMemberPhoto
 *
 * @ORM\Table(name="contest__member_image")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class ContestMemberImage
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
   * @Assert\File(maxSize="5M", mimeTypes={"image/png", "image/jpeg", "image/pjpeg"})
   * @Vich\UploadableField(mapping="contests", fileNameProperty="image_filename")
   * @var UploadedFile $image
   */
  private $image;

  /**
   * @var string
   * @ORM\Column(type="string", length=50)
   */
  private $image_filename;

  /**
   * @var integer
   * @ORM\Column(type="integer")
   * @Assert\NotNull
   * @Assert\Range(min=0)
   */
  private $position = 0;

  /**
   * @var \DateTime
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $created_at;

  /**
   * @var ContestMember
   * @ORM\ManyToOne(targetEntity="ContestMember", inversedBy="images")
   */
  private $contest_member;

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
   * Set createdAt
   *
   * @param \DateTime $createdAt
   * @return ContestMemberImage
   */
  public function setCreatedAt($createdAt)
  {
    $this->created_at = $createdAt;

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

  /**
   * @param \Symfony\Component\HttpFoundation\File\UploadedFile $image
   * @return $this
   */
  public function setImage($image)
  {
    $this->image = $image;

    return $this;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\File\UploadedFile
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * @param int $position
   * @return $this
   */
  public function setPosition($position)
  {
    $this->position = $position;
    return $this;
  }

  /**
   * @return int
   */
  public function getPosition()
  {
    return $this->position;
  }


  /**
   * Sets contest member
   * @param ContestMember $member
   * @return $this
   */
  public function setContestMember(ContestMember $member)
  {
    $this->contest_member = $member;

    return $this;
  }

  /**
   * @return \Rodgermd\ContestNominationsBundle\Entity\ContestMember
   */
  public function getContestMember()
  {
    return $this->contest_member;
  }


}
