<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city", indexes={@ORM\Index(name="fk_city_department1", columns={"department_id_department"})})
 * @ORM\Entity
 */
class City
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_city", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idCity;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Department
     *
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Department", inversedBy="citys")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department_id_department", referencedColumnName="id_department")
     * })
     */
    private $departmentDepartment;

    /**
     * @ORM\Column(type="string", length=100, nullable=TRUE)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cityCode;


    /**
     * Set idCity
     *
     * @param integer $idCity
     *
     * @return City
     */
    public function setIdCity($idCity)
    {
        $this->idCity = $idCity;

        return $this;
    }

    /**
     * Get idCity
     *
     * @return integer
     */
    public function getIdCity()
    {
        return $this->idCity;
    }

    /**
     * Set departmentDepartment
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Department $departmentDepartment
     *
     * @return City
     */
    public function setDepartmentDepartment(\RocketSeller\TwoPickBundle\Entity\Department $departmentDepartment)
    {
        $this->departmentDepartment = $departmentDepartment;

        return $this;
    }

    /**
     * Get departmentDepartment
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Department
     */
    public function getDepartmentDepartment()
    {
        return $this->departmentDepartment;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return City
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
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Set cityCode
     *
     * @param integer $cityCode
     *
     * @return City
     */
    public function setCityCode($cityCode)
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * Get cityCode
     *
     * @return integer
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }
}
