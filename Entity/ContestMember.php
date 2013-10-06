<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Site\BaseBundle\Entity\AbstractTranslation;
use Site\BaseBundle\Entity\Member;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ContestMember
 *
 * @ORM\Table(name="contest__member", uniqueConstraints={
 * @ORM\UniqueConstraint(name="contest_member_unique_idx", columns={"contest_id", "member_id"})
 * })
 * @ORM\Entity(repositoryClass="Rodgermd\ContestNominationsBundle\Entity\ContestMemberRepository")
 * @Vich\Uploadable
 * @Gedmo\TranslationEntity(class="Rodgermd\ContestNominationsBundle\Entity\ContestMemberTranslation")
 * @UniqueEntity({"contest", "member"})
 */
class ContestMember
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
   * @Gedmo\Translatable
   * @ORM\Column(name="company", type="string", length=255, nullable=true)
   */
  private $company = '';

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(name="position", type="string", length=255)
   * @Assert\NotNull(message="Position in company is required")
   */
  private $position = '';

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(name="experience", type="string", length=255)
   * @Assert\NotNull(message="Experience is required")
   */
  private $experience = '';

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(name="description", type="string", length=255)
   * @Assert\NotNull(message="Description is required")
   */
  private $description = '';

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="created_at", type="datetime")
   * @Gedmo\Timestampable(on="create")
   */
  private $created_at;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="updated_at", type="datetime")
   * @Gedmo\Timestampable(on="update")
   */
  private $updated_at;

  /**
   * @var Member $member
   * @ORM\ManyToOne(targetEntity="Site\BaseBundle\Entity\Member", inversedBy="contest_members")
   *
   */
  private $member;

  /**
   * @var Contest $contest
   * @ORM\ManyToOne(targetEntity="Contest", inversedBy="contest_members")
   */
  private $contest;

  /**
   * @Assert\File(maxSize="5M", mimeTypes={"image/png", "image/jpeg", "image/pjpeg"})
   * @Vich\UploadableField(mapping="contests", fileNameProperty="image_filename")
   * @var UploadedFile $image
   */
  private $image;

  /**
   * @var string
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $image_filename;

  /**
   * @var PersistentCollection
   * @ORM\OneToMany(targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestMemberImage", mappedBy="contest_member")
   */
  private $images;

  /**
   * @Gedmo\Locale
   */
  protected $locale;

  /**
   * @ORM\OneToMany(
   *   targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestMemberTranslation",
   *   mappedBy="object",
   *   cascade={"persist", "remove"}
   * )
   */
  private $translations;

  /**
   * @var PersistentCollection $votes
   * @ORM\OneToMany(targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestVote", mappedBy="contest_member")
   */
  private $votes;

  /**
   * @var integer $votes_number
   * @ORM\Column(type="integer")
   * @Assert\NotNull(message="Votes number is required")
   * @Assert\Range(min="0", minMessage="Votes number must be positive")
   */
  private $votes_number = 0;

  protected $place;

  public function __construct()
  {
    $this->translations = new ArrayCollection();
    $this->images       = new ArrayCollection();
    $this->votes        = new ArrayCollection();
  }


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
   * Set company
   *
   * @param string $company
   * @return ContestMember
   */
  public function setCompany($company)
  {
    $this->company = $company;

    return $this;
  }

  /**
   * Get company
   *
   * @return string
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * Set position
   *
   * @param string $position
   * @return ContestMember
   */
  public function setPosition($position)
  {
    $this->position = $position;

    return $this;
  }

  /**
   * Get position
   *
   * @return string
   */
  public function getPosition()
  {
    return $this->position;
  }

  /**
   * Set experience
   *
   * @param string $experience
   * @return ContestMember
   */
  public function setExperience($experience)
  {
    $this->experience = $experience;

    return $this;
  }

  /**
   * Get experience
   *
   * @return string
   */
  public function getExperience()
  {
    return $this->experience;
  }

  /**
   * Set description
   *
   * @param string $description
   * @return ContestMember
   */
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get description
   *
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set createdAt
   *
   * @param \DateTime $createdAt
   * @return ContestMember
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
   * Set updatedAt
   *
   * @param \DateTime $updatedAt
   * @return ContestMember
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updated_at = $updatedAt;

    return $this;
  }

  /**
   * Get updatedAt
   *
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
    return $this->updated_at;
  }

  /**
   * @param Contest $contest
   * @return $this
   */
  public function setContest(Contest $contest)
  {
    $this->contest = $contest;

    return $this;
  }

  /**
   * @return Contest
   */
  public function getContest()
  {
    return $this->contest;
  }

  /**
   * @param \Site\BaseBundle\Entity\Member $member
   * @return $this
   */
  public function setMember(Member $member)
  {
    $this->member = $member;

    return $this;
  }

  /**
   * @return \Site\BaseBundle\Entity\Member
   */
  public function getMember()
  {
    return $this->member;
  }

  /**
   * Sets image
   * @param UploadedFile|array $image
   * @return $this
   */
  public function setImage($image = null)
  {
    if (is_array($image)) {
      if ($image['delete']) $this->image_filename = null;
      $image = $image['file'];
    }

    $this->image      = $image;
    $this->updated_at = null;

    return $this;
  }

  /**
   * Gets image
   * @return UploadedFile
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * Sets related photos
   * @param \Doctrine\ORM\PersistentCollection $photos
   * @return $this
   */
  public function setImages($photos)
  {
    $this->images = $photos;

    return $this;
  }

  /**
   * Gets related photos
   * @return \Doctrine\ORM\PersistentCollection
   */
  public function getImages()
  {
    return $this->images;
  }

  public function setTranslatableLocale($locale)
  {
    $this->locale = $locale;

    return $this;
  }

  public function getLocale()
  {
    return $this->locale;
  }

  /**
   * Gets translations
   * @return ArrayCollection
   */
  public function getTranslations()
  {
    return $this->translations;
  }

  /**
   * Adds translation
   * @param AbstractTranslation $t
   * @return $this
   */
  public function addTranslation(AbstractTranslation $t)
  {
    if (!$this->translations->contains($t)) {
      $this->translations[] = $t;
      $t->setObject($this);
    }

    return $this;
  }

  public function __toString()
  {
    return (string)$this->getMember();
  }

  /**
   * Gets place
   * @return bool|integer
   */
  public function getPlace()
  {
    return $this->getVotes()->count() ? $this->place : false;
  }

  /**
   * Sets place
   * @param $place
   * @return $this
   */
  public function setPlace($place)
  {
    $this->place = $place;

    return $this;
  }

  /**
   * @return integer
   */
  public function getVotesNumber()
  {
    return $this->votes_number;
  }

  public function setVotesNumber($votes_number)
  {
    $this->votes_number = $votes_number;

    return $this;
  }

  /**
   * Adds vote into collection
   * @param ContestVote $vote
   * @return $this
   */
  public function addVote(ContestVote $vote)
  {
    $this->votes[]      = $vote;
    $this->votes_number = $this->votes->count();

    return $this;
  }

  /**
   * Removes vote from collection
   * @param ContestVote $vote
   * @return $this
   */
  public function removeVote(ContestVote $vote)
  {
    $this->votes->removeElement($vote);
    $this->votes_number = $this->votes->count();

    return $this;
  }

  /**
   * Gets related votes
   * @return \Doctrine\ORM\PersistentCollection
   */
  public function getVotes()
  {
    return $this->votes;
  }

  public function toArray()
  {
    return array(
      'id'          => $this->getId(),
      'company'     => $this->getCompany(),
      'position'    => $this->getPosition(),
      'experience'  => $this->getExperience(),
      'description' => $this->getDescription(),
      'place' => $this->getPlace(),
      'votes' => $this->getVotes()->count()
    );
  }
}
