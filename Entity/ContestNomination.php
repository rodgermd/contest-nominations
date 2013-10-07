<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Site\BaseBundle\Entity\AbstractBackgroundEntity;
use Site\BaseBundle\Entity\AbstractTranslation;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ContestNomination
 *
 * @ORM\Table(name="contest__nominations")
 * @ORM\Entity(repositoryClass="Rodgermd\ContestNominationsBundle\Entity\ContestNominationRepository")
 * @Gedmo\TranslationEntity(class="Rodgermd\ContestNominationsBundle\Entity\ContestNominationTranslation")
 * @Vich\Uploadable
 */
class ContestNomination extends AbstractBackgroundEntity
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
   * @Gedmo\Locale
   */
  protected $locale;

  /**
   * @var string $slug
   * @Gedmo\Slug(handlers={
   *    @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
   *    @Gedmo\SlugHandlerOption(name="relationField", value="contest"),
   *    @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug")
   *      })
   * },
   * fields={"title"}
   * )
   * @ORM\Column(name="slug", type="string", length=255, unique=true)
   */
  protected $slug;

  /**
   * @var PersistentCollection $member_contests
   * @ORM\OneToMany(targetEntity="ContestMember", mappedBy="contest_nomination")
   */
  private $contest_members;

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
   *   targetEntity="Rodgermd\ContestNominationsBundle\Entity\ContestNominationTranslation",
   *   mappedBy="object",
   *   cascade={"persist", "remove"}
   * )
   */
  private $translations;

  /**
   * @var Contest
   * @ORM\ManyToOne(targetEntity="Rodgermd\ContestNominationsBundle\Entity\Contest", inversedBy="nominations")
   * @ORM\JoinColumn(name="contest_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $contest;

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
   * Sets Contest
   * @param \Rodgermd\ContestNominationsBundle\Entity\Contest $contest
   * @return $this
   */
  public function setContest(Contest $contest)
  {
    $this->contest = $contest;
    return $this;
  }

  /**
   * Gets contest
   * @return \Rodgermd\ContestNominationsBundle\Entity\Contest
   */
  public function getContest()
  {
    return $this->contest;
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

}
