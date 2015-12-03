<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document", indexes={@ORM\Index(name="fk_Document_document_type1", columns={"document_type_id_document_type"}), @ORM\Index(name="fk_document_contract1", columns={"contract_id_contract"}), @ORM\Index(name="fk_document_person1", columns={"person_id_person"})})
 * @ORM\Entity
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_document", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idDocument;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Person
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Person", inversedBy="docs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id_person", referencedColumnName="id_person")
     * })
     */
    private $personPerson;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Contract
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id_contract", referencedColumnName="id_contract")
     * })
     */
    private $contractContract;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\DocumentType
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\DocumentType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="document_type_id_document_type", referencedColumnName="id_document_type")
     * })
     */
    private $documentTypeDocumentType;
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media", mappedBy="documentDocument", cascade={"persist"})
     */
    private $mediaMedium;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * Set idDocument
     *
     * @param integer $idDocument
     *
     * @return Document
     */
    public function setIdDocument($idDocument)
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    /**
     * Get idDocument
     *
     * @return integer
     */
    public function getIdDocument()
    {
        return $this->idDocument;
    }

    /**
     * Set personPerson
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Person $personPerson
     *
     * @return Document
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
     * Set contractContract
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Contract $contractContract
     *
     * @return Document
     */
    public function setContractContract(\RocketSeller\TwoPickBundle\Entity\Contract $contractContract)
    {
        $this->contractContract = $contractContract;

        return $this;
    }

    /**
     * Get contractContract
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Contract
     */
    public function getContractContract()
    {
        return $this->contractContract;
    }

    /**
     * Set documentTypeDocumentType
     *
     * @param \RocketSeller\TwoPickBundle\Entity\DocumentType $documentTypeDocumentType
     *
     * @return Document
     */
    public function setDocumentTypeDocumentType(\RocketSeller\TwoPickBundle\Entity\DocumentType $documentTypeDocumentType)
    {
        $this->documentTypeDocumentType = $documentTypeDocumentType;

        return $this;
    }

    /**
     * Get documentTypeDocumentType
     *
     * @return \RocketSeller\TwoPickBundle\Entity\DocumentType
     */
    public function getDocumentTypeDocumentType()
    {
        return $this->documentTypeDocumentType;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mediaMedia = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add mediaMedium
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaMedium
     *
     * @return Document
     */
    public function addMediaMedium(\Application\Sonata\MediaBundle\Entity\Media $mediaMedium)
    {
        $this->mediaMedium[] = $mediaMedium;

        return $this;
    }

    /**
     * Remove mediaMedium
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaMedium
     */
    public function removeMediaMedium(\Application\Sonata\MediaBundle\Entity\Media $mediaMedium)
    {
        $this->mediaMedium->removeElement($mediaMedium);
    }

    /**
     * Get mediaMedium
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaMedium()
    {
        return $this->mediaMedium;
    }
}
