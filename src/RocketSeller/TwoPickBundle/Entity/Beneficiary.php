<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Beneficiary
 *
 * @ORM\Table(name="beneficiary", indexes={@ORM\Index(name="fk_beneficiary_person1", columns={"person_id_person"})})
 * @ORM\Entity
 */
class Beneficiary
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_beneficiary", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idBeneficiary;

    /**
     * @var \AppBundle\Entity\Person
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id_person", referencedColumnName="id_person")
     * })
     */
    private $personPerson;



    /**
     * Set idBeneficiary
     *
     * @param integer $idBeneficiary
     *
     * @return Beneficiary
     */
    public function setIdBeneficiary($idBeneficiary)
    {
        $this->idBeneficiary = $idBeneficiary;

        return $this;
    }

    /**
     * Get idBeneficiary
     *
     * @return integer
     */
    public function getIdBeneficiary()
    {
        return $this->idBeneficiary;
    }

    /**
     * Set personPerson
     *
     * @param \AppBundle\Entity\Person $personPerson
     *
     * @return Beneficiary
     */
    public function setPersonPerson(\AppBundle\Entity\Person $personPerson)
    {
        $this->personPerson = $personPerson;

        return $this;
    }

    /**
     * Get personPerson
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPersonPerson()
    {
        return $this->personPerson;
    }
}
