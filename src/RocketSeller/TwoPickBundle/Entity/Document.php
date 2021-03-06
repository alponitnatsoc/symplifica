<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * Document
 *
 * @ORM\Table(name="document", indexes={@ORM\Index(name="fk_Document_document_type1", columns={"document_type_id_document_type"}), @ORM\Index(name="fk_document_person1", columns={"person_id_person"})})
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
     * @var \RocketSeller\TwoPickBundle\Entity\DocumentType
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\DocumentType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="document_type_id_document_type", referencedColumnName="id_document_type")
     * })
     */
    private $documentTypeDocumentType;
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", mappedBy="documentDocument", cascade={"persist"}, fetch="LAZY")
     */
    private $mediaMedia;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Employer
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Employer")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="employer_id_employer", referencedColumnName="id_employer")
     * })
     * @Exclude
     */
    private $employerEmployer;

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
     * Set personPerson
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Person $personPerson
     *
     * @return Document
     */
    public function setPersonPerson(\RocketSeller\TwoPickBundle\Entity\Person $personPerson = null)
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
    public function setContractContract(\RocketSeller\TwoPickBundle\Entity\Contract $contractContract = null)
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
    public function setDocumentTypeDocumentType(\RocketSeller\TwoPickBundle\Entity\DocumentType $documentTypeDocumentType = null)
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
     * Set mediaMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaMedia
     *
     * @return Document
     */
    public function setMediaMedia(\Application\Sonata\MediaBundle\Entity\Media $mediaMedia = null)
    {
        $this->mediaMedia = $mediaMedia;
        $mediaMedia->setDocumentDocument($this);
        return $this;
    }

    /**
     * Get mediaMedia
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMediaMedia()
    {

        return $this->mediaMedia;

    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Document
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set employerEmployer
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Employer $employerEmployer
     *
     * @return Document
     */
    public function setEmployerEmployer(\RocketSeller\TwoPickBundle\Entity\Employer $employerEmployer = null)
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

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getPath()
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $service = $kernel->getContainer()->get('sonata.media.twig.extension');

        if($this->getMediaMedia()){
            return '//' . $_SERVER['HTTP_HOST'] . $service->path($this->getMediaMedia(), 'reference');
        }else{
            return '';
        }

    }
}
