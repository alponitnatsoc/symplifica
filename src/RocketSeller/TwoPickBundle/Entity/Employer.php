<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employer
 *
 * @ORM\Table(name="employer", indexes={@ORM\Index(name="fk_employer_person1", columns={"person_id_person"})})
 * @ORM\Entity
 */
class Employer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_employer", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEmployer;
    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    private $birthAddress;
    /**
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_birth_department", referencedColumnName="id_department")
     * })
     */
    private $birthDepartment;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="id_birth_city", referencedColumnName="id_city")
     */
    private $birthCity;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Person
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Person", inversedBy="employer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id_person", referencedColumnName="id_person")
     * })
     */
    private $personPerson;
    /**
     * @ORM\OneToMany(targetEntity="Workplace", mappedBy="employerEmployer" , cascade={"persist", "remove"})
     */
    private $workplaces;

    /**
     * @ORM\OneToMany(targetEntity="Procedure", mappedBy="employerEmployer", cascade={"persist"})
     */
    private $procedure;




    /**
     * Set idEmployer
     *
     * @param integer $idEmployer
     *
     * @return Employer
     */
    public function setIdEmployer($idEmployer)
    {
        $this->idEmployer = $idEmployer;

        return $this;
    }

    /**
     * Get idEmployer
     *
     * @return integer
     */
    public function getIdEmployer()
    {
        return $this->idEmployer;
    }

    /**
     * Set personPerson
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Person $personPerson
     *
     * @return Employer
     */
    public function setPersonPerson(\RocketSeller\TwoPickBundle\Entity\Person $personPerson)
    {
        $this->personPerson = $personPerson;

        return $this;
    }

    /**
     * Get personPerson
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Person
     */
    public function getPersonPerson()
    {
        return $this->personPerson;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workplaces = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add workplace
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Workplace $workplace
     *
     * @return Employer
     */
    public function addWorkplace(\RocketSeller\TwoPickBundle\Entity\Workplace $workplace)
    {
        $workplace->setEmployerEmployer($this);
        $this->workplaces[] = $workplace;

        return $this;
    }

    /**
     * Remove workplace
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Workplace $workplace
     */
    public function removeWorkplace(\RocketSeller\TwoPickBundle\Entity\Workplace $workplace)
    {
        $this->workplaces->removeElement($workplace);
    }

    /**
     * Get workplaces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkplaces()
    {
        return $this->workplaces;
    }



    /**
     * Add procedure
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Procedure $procedure
     *
     * @return Employer
     */
    public function addProcedure(\RocketSeller\TwoPickBundle\Entity\Procedure $procedure)
    {
        $this->procedure[] = $procedure;

        return $this;
    }

    /**
     * Remove procedure
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Procedure $procedure
     */
    public function removeProcedure(\RocketSeller\TwoPickBundle\Entity\Procedure $procedure)
    {
        $this->procedure->removeElement($procedure);
    }

    /**
     * Get procedure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Set birthAddress
     *
     * @param string $birthAddress
     *
     * @return Employer
     */
    public function setBirthAddress($birthAddress)
    {
        $this->birthAddress = $birthAddress;

        return $this;
    }

    /**
     * Get birthAddress
     *
     * @return string
     */
    public function getBirthAddress()
    {
        return $this->birthAddress;
    }

    /**
     * Set birthDepartment
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Department $birthDepartment
     *
     * @return Employer
     */
    public function setBirthDepartment(\RocketSeller\TwoPickBundle\Entity\Department $birthDepartment = null)
    {
        $this->birthDepartment = $birthDepartment;

        return $this;
    }

    /**
     * Get birthDepartment
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Department
     */
    public function getBirthDepartment()
    {
        return $this->birthDepartment;
    }

    /**
     * Set birthCity
     *
     * @param \RocketSeller\TwoPickBundle\Entity\City $birthCity
     *
     * @return Employer
     */
    public function setBirthCity(\RocketSeller\TwoPickBundle\Entity\City $birthCity = null)
    {
        $this->birthCity = $birthCity;

        return $this;
    }

    /**
     * Get birthCity
     *
     * @return \RocketSeller\TwoPickBundle\Entity\City
     */
    public function getBirthCity()
    {
        return $this->birthCity;
    }
}
