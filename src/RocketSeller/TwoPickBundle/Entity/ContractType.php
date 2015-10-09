<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContractType
 *
 * @ORM\Table(name="contract_type")
 * @ORM\Entity
 */
class ContractType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_contract_type", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idContractType;



    /**
     * Get idContractType
     *
     * @return integer
     */
    public function getIdContractType()
    {
        return $this->idContractType;
    }
}
