<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterType
 *
 * @ORM\Table(name="filter_type")
 * @ORM\Entity
 */
class FilterType
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_filter_type", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFilterType;

    /**
     * Get idFilterType
     *
     * @return integer
     */
    public function getIdFilterType()
    {
        return $this->idFilterType;
    }
}
