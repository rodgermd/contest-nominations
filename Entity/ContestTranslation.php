<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Site\BaseBundle\Entity\AbstractTranslation;

/**
 * Class ContestTranslation
 * @package Rodgermd\ContestNominationsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="contest__translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ContestTranslation extends AbstractTranslation
{
  /**
   * @ORM\ManyToOne(targetEntity="Contest", inversedBy="translations")
   * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
   * @var Exhibition
   */
  protected $object;
}