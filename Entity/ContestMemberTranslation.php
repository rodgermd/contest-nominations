<?php

namespace Rodgermd\ContestNominationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Site\BaseBundle\Entity\AbstractTranslation;

/**
 * Class ContestMemberTranslation
 * @package Rodgermd\ContestNominationsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="contest__member_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ContestMemberTranslation extends AbstractTranslation
{
  /**
   * @ORM\ManyToOne(targetEntity="ContestMember", inversedBy="translations")
   * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $object;
}