<?php

namespace RocketSeller\TwoPickBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PayrollDetail
 *
 * @ORM\Table(name="payroll_detail", indexes={@ORM\Index(name="fk_payroll_detail_payroll1", columns={"payroll_id_payroll"})})
 * @ORM\Entity
 */
class PayrollDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_payroll_detail", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idPayrollDetail;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Payroll
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Payroll", inversedBy="payrollDetails")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payroll_id_payroll", referencedColumnName="id_payroll")
     * })
     */
    private $payrollPayroll;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    private $description;

    /**
     * Set idPayrollDetail
     *
     * @param integer $idPayrollDetail
     *
     * @return PayrollDetail
     */
    public function setIdPayrollDetail($idPayrollDetail)
    {
        $this->idPayrollDetail = $idPayrollDetail;

        return $this;
    }

    /**
     * Get idPayrollDetail
     *
     * @return integer
     */
    public function getIdPayrollDetail()
    {
        return $this->idPayrollDetail;
    }

    /**
     * Set payrollPayroll
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Payroll $payrollPayroll
     *
     * @return PayrollDetail
     */
    public function setPayrollPayroll(\RocketSeller\TwoPickBundle\Entity\Payroll $payrollPayroll)
    {
        $this->payrollPayroll = $payrollPayroll;

        return $this;
    }

    /**
     * Get payrollPayroll
     *
     * @return \RocketSeller\TwoPickBundle\Entity\Payroll
     */
    public function getPayrollPayroll()
    {
        return $this->payrollPayroll;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PayrollDetail
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
