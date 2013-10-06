<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Site\BaseBundle\Entity\AbstractBackgroundEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Contest
 *
 * @ORM\Table(name="contest")
 * @ORM\Entity(repositoryClass="Rodgermd\ContestNominationsBundle\Entity\ContestRepository")
 * @Gedmo\TranslationEntity(class="Rodgermd\ContestNominationsBundle\Entity\ContestTranslation")
 * @Vich\Uploadable
 * @Assert\Callback(methods={"isEndDateValid"})
 */
class Contest extends AbstractBackgroundEntity
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
   * @ORM\Column(name="title", type="string", length=255)
   */
  protected $title;

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(type="text", nullable=true)
   */
  protected $metaKeywords;

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(type="text", nullable=true)
   */
  protected $metaDescription;

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(type="text", nullable=true)
   */
  protected $h1;

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(type="text", nullable=true)
   */
  protected $content;

  /**
   * @var \DateTime
   * @Assert\NotNull(message="validation.contest.fields.required.end_date")
   * @ORM\Column(name="end_date", type="date")
   */
  private $end_date;

  /**
   * @Gedmo\Locale
   */
  protected $locale;

  /**
   * @var PersistentCollection $member_contests
   * @ORM\OneToMany(targetEntity="ContestMember", mappedBy="contest")
   */
  private $contest_members;

  /**
   * @var string
   * @Gedmo\Translatable
   * @ORM\Column(type="string", nullable=true)
   */
  private $video_url;

  /**
   * @var string $image_filename
   * @ORM\Column(name="image_filename", type="string", length=50, nullable=true)
   */
  protected $image_filename;

  /**
   * @var string image
   * @Assert\File(maxSize="5M", mimeTypes={"image/png", "image/jpeg", "image/pjpeg"})
   * @Vich\UploadableField(mapping="contests", fileNameProperty="image_filename")
   * @var UploadedFile $imagefile
   */
  protected $image;

  /**
   * @ORM\OneToMany(
   *   targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestTranslation",
   *   mappedBy="object",
   *   cascade={"persist", "remove"}
   * )
   */
  private $translations;

  public function __construct()
  {
    $this->translations    = new ArrayCollection();
    $this->contest_members = new ArrayCollection();
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
   * @param string $content
   * @return $this
   */
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set endDate
   *
   * @param \DateTime $endDate
   * @return Contest
   */
  public function setEndDate($endDate)
  {
    $this->end_date = $endDate;

    return $this;
  }

  /**
   * Get endDate
   * @return \DateTime
   */
  public function getEndDate()
  {
    return $this->end_date;
  }

  /**
   * Sets video url
   * @param string $video_url
   * @return $this
   */
  public function setVideoUrl($video_url)
  {
    $this->video_url = $video_url;
    return $this;
  }

  /**
   * Gets video url
   * @return string
   */
  public function getVideoUrl()
  {
    return $this->video_url;
  }

  /**
   * Gets related ContestMember objects
   * @return PersistentCollection
   */
  public function getContestMembers()
  {
    return $this->contest_members;
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

  public function isEndDateValid(ExecutionContextInterface $context)
  {
    if ($this->end_date < new \DateTime())
    {
      $tomorrow = new \DateTime('+1 day');
      $context->addViolationAt('end_date', 'The date {{ date }} is not valid. Minimum date is {{ mindate }}', array(
        '{{ date }}' => $this->end_date->format('Y-m-d'),
        '{{ mindate }}' => $tomorrow->format('Y-m-d'),
      ));
    }
  }
}