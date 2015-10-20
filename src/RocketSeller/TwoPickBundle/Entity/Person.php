<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_person", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPerson;

    /**
     * @ORM\Column(type="string", length=100, nullable=TRUE)
     */
    private $names;

    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    private $lastName1;

    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    private $lastName2;

    /**
     * @ORM\Column(type="string", length=20, nullable=TRUE)
     */
    private $documentType;
    /**
     * @ORM\Column(type="string", length=20, nullable=TRUE)
     */
    private $document;
    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    private $email;
    /**
     * @ORM\Column(type="date", length=20, nullable=TRUE)
     */
    private $birthDate;
    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    private $mainAddress;

    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    private $neighborhood;
    /**
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_department", referencedColumnName="id_department")
     * })
     */
    private $department;
    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    private $phone;
    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="id_city", referencedColumnName="id_city")
     */
    private $city;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Employer
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Employer", mappedBy="personPerson", cascade={"persist", "remove"})
     */
    private $employer;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="personPerson", cascade={"persist"})
     */
    private $docs;

    /**
     * Get idPerson
     *
     * @return integer
     */
    public function getIdPerson()
    {
        return $this->idPerson;
    }



    /**
     * Set names
     *
     * @param string $names
     *
     * @return Person
     */
    public function setNames($names)
    {
        $this->names = $names;

        return $this;
    }

    /**
     * Get names
     *
     * @return string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Set lastName1
     *
     * @param string $lastName1
     *
     * @return Person
     */
    public function setLastName1($lastName1)
    {
        $this->lastName1 = $lastName1;

        return $this;
    }

    /**
     * Get lastName1
     *
     * @return string
     */
    public function getLastName1()
    {
        return $this->lastName1;
    }

    /**
     * Set lastName2
     *
     * @param string $lastName2
     *
     * @return Person
     */
    public function setLastName2($lastName2)
    {
        $this->lastName2 = $lastName2;

        return $this;
    }

    /**
     * Get lastName2
     *
     * @return string
     */
    public function getLastName2()
    {
        return $this->lastName2;
    }

    /**
     * Set documentType
     *
     * @param string $documentType
     *
     * @return Person
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return Person
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Person
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }



    /**
     * Set mainAddress
     *
     * @param string $mainAddress
     *
     * @return Person
     */
    public function setMainAddress($mainAddress)
    {
        $this->mainAddress = $mainAddress;

        return $this;
    }

    /**
     * Get mainAddress
     *
     * @return string
     */
    public function getMainAddress()
    {
        return $this->mainAddress;
    }

    /**
     * Set neighborhood
     *
     * @param string $neighborhood
     *
     * @return Person
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get neighborhood
     *
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set department
     *
     * @param string $department
     *
     * @return Person
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Person
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set city
     *
     * @param \RocketSeller\TwoPickBundle\Entity\City $city
     *
     * @return Person
     */
    public function setCity(\RocketSeller\TwoPickBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \RocketSeller\TwoPickBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }



    /**
     * Set employer
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Employer $employer
     *
     * @return Person
     */
    public function setEmployer(\RocketSeller\TwoPickBundle\Entity\Employer $employer = null)
    {
        $employer->setPersonPerson($this);
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Employer
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add doc
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Document $doc
     *
     * @return Person
     */
    public function addDoc(\RocketSeller\TwoPickBundle\Entity\Document $doc)
    {
        $this->docs[] = $doc;

        return $this;
    }

    /**
     * Remove doc
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Document $doc
     */
    public function removeDoc(\RocketSeller\TwoPickBundle\Entity\Document $doc)
    {
        $this->docs->removeElement($doc);
    }

    /**
     * Get docs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocs()
    {
        return $this->docs;
    }
}
