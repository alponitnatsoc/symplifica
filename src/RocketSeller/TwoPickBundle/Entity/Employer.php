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
     * @var \RocketSeller\TwoPickBundle\Entity\Person
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id_person", referencedColumnName="id_person")
     * })
     */
    private $personPerson;
    /**
     * @ORM\OneToMany(targetEntity="Workplace", mappedBy="employerEmployer" , cascade={"persist"})
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
}
