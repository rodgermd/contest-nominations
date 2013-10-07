<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Site\BaseBundle\Entity\AbstractTranslation;

/**
 * Class ContestNominationTranslation
 * @package Rodgermd\ContestNominationsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="contest__nominations_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ContestNominationTranslation extends AbstractTranslation
{
  /**
   * @ORM\ManyToOne(targetEntity="ContestNomination", inversedBy="translations")
   * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
   * @var ContestNomination
   */
  protected $object;
}