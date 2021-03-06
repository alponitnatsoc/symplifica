<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * EmployeeHasEntity
 *
 * @ORM\Table(name="employee_has_entity", indexes={@ORM\Index(name="fk_employee_has_entity_employee1", columns={"employee_id_employee"}), @ORM\Index(name="fk_employee_has_entity_entity1", columns={"entity_id_entity"})})
 * @ORM\Entity
 */
class EmployeeHasEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_employee_has_entity", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEmployeeHasEntity;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Entity
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Entity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entity_id_entity", referencedColumnName="id_entity")
     * })
     */
    private $entityEntity;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Employee
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Employee", inversedBy="entities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id_employee", referencedColumnName="id_employee")
     * })
     * @Exclude
     */
    private $employeeEmployee;

    /**
     * -1 - sisben
     * 0 - validar
     * 1 - inscribir
     * 2 - error
     * 3 - confirmada
     * @ORM\Column(type="smallint", nullable=TRUE)
     */
    private $state =0 ;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Document
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Document")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radicated_id_document", referencedColumnName="id_document")
     * })
     * @Exclude
     */
    private $radicatedDocument;

    /**
     * Set idEmployeeHasEntity
     *
     * @param integer $idEmployeeHasEntity
     *
     * @return EmployeeHasEntity
     */
    public function setIdEmployeeHasEntity($idEmployeeHasEntity)
    {
        $this->idEmployeeHasEntity = $idEmployeeHasEntity;

        return $this;
    }

    /**
     * Get idEmployeeHasEntity
     *
     * @return integer
     */
    public function getIdEmployeeHasEntity()
    {
        return $this->idEmployeeHasEntity;
    }

    /**
     * Set entityEntity
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Entity $entityEntity
     *
     * @return EmployeeHasEntity
     */
    public function setEntityEntity(\RocketSeller\TwoPickBundle\Entity\Entity $entityEntity)
    {
        $this->entityEntity = $entityEntity;

        return $this;
    }

    /**
     * Get entityEntity
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Entity
     */
    public function getEntityEntity()
    {
        return $this->entityEntity;
    }

    /**
     * Set employeeEmployee
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Employee $employeeEmployee
     *
     * @return EmployeeHasEntity
     */
    public function setEmployeeEmployee(\RocketSeller\TwoPickBundle\Entity\Employee $employeeEmployee)
    {
        $this->employeeEmployee = $employeeEmployee;

        return $this;
    }

    /**
     * Get employeeEmployee
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Employee
     */
    public function getEmployeeEmployee()
    {
        return $this->employeeEmployee;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return EmployeeHasEntity
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set radicatedDocument
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Document $radicatedDocument
     *
     * @return EmployeeHasEntity
     */
    public function setRadicatedDocument(\RocketSeller\TwoPickBundle\Entity\Document $radicatedDocument = null)
    {
        $this->radicatedDocument = $radicatedDocument;

        return $this;
    }

    /**
     * Get radicatedDocument
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Document
     */
    public function getRadicatedDocument()
    {
        return $this->radicatedDocument;
    }
}
