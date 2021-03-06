<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmployerHasEntity
 *
 * @ORM\Table(name="employer_has_entity", indexes={@ORM\Index(name="fk_employer_has_entity_employer1", columns={"employer_id_employer"}), @ORM\Index(name="fk_employer_has_entity_entity1", columns={"entity_id_entity"})})
 * @ORM\Entity
 */
class EmployerHasEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_employer_has_entity", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEmployerHasEntity;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Entity
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Entity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entity_id_entity", referencedColumnName="id_entity")
     * })
     */
    private $entityEntity;

    /**
     * 0 - validar
     * 1 - inscribir
     * 2 - confirmada
     * @ORM\Column(type="smallint", nullable=TRUE)
     */
    private $state =0 ;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Employer
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Employer", inversedBy="entities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employer_id_employer", referencedColumnName="id_employer")
     * })
     */
    private $employerEmployer;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Document
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Document")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radicated_id_document", referencedColumnName="id_document")
     * })
     */
    private $radicatedDocument;

    /**
     * Set idEmployerHasEntity
     *
     * @param integer $idEmployerHasEntity
     *
     * @return EmployerHasEntity
     */
    public function setIdEmployerHasEntity($idEmployerHasEntity)
    {
        $this->idEmployerHasEntity = $idEmployerHasEntity;

        return $this;
    }

    /**
     * Get idEmployerHasEntity
     *
     * @return integer
     */
    public function getIdEmployerHasEntity()
    {
        return $this->idEmployerHasEntity;
    }

    /**
     * Set entityEntity
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Entity $entityEntity
     *
     * @return EmployerHasEntity
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
     * Set employerEmployer
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Employer $employerEmployer
     *
     * @return EmployerHasEntity
     */
    public function setEmployerEmployer(\RocketSeller\TwoPickBundle\Entity\Employer $employerEmployer)
    {
        $this->employerEmployer = $employerEmployer;

        return $this;
    }

    /**
     * Get employerEmployer
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Employer
     */
    public function getEmployerEmployer()
    {
        return $this->employerEmployer;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return EmployerHasEntity
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
     * @return EmployerHasEntity
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
