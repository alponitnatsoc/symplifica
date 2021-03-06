<?php

namespace RocketSeller\TwoPickBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use RocketSeller\TwoPickBundle\RocketSellerTwoPickBundle;

/**
 * RealProcedure
 *
 * @ORM\Table(name="real_procedure", indexes={
 *     @ORM\Index(name="fk_procedure_procedure_type1", columns={"procedure_type_id_procedure_type"}),
 *     @ORM\Index(name="fk_procedure_user1", columns={"user_id_user"}),
 *     @ORM\Index(name="fk_procedure_employer1", columns={"employer_id_employer"}),
 *     @ORM\Index(name="procedure_status_index", columns={"status_id_procedure"}),
 *     @ORM\Index(name="procedure_type_index", columns={"procedure_type_id_procedure_type"}),
 *     @ORM\Index(name="procedure_action_change_at_index", columns={"action_changed_at"})
 * })
 * @ORM\Entity
 */
class RealProcedure
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_procedure", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idProcedure;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\User", inversedBy="realProcedures", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id_user", referencedColumnName="id")
     * })
     */
    private $userUser;

    /**
     * @ORM\Column(name="created_at",type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="action_changed_at",type="datetime",nullable=true)
     */
    private $actionChangedAt=null;

    /**
     * @ORM\Column(name="status_updated_at",type="datetime",nullable=true)
     */
    private $statusUpdatedAt=null;

    /**
     * @ORM\Column(name="back_office_date",type="datetime",nullable=true)
     */
    private $backOfficeDate=null;

    /**
     * @ORM\Column(name="finished_at",type="datetime",nullable=true)
     */
    private $finishedAt=null;

    /**
     * @ORM\Column(name="first_error_at",type="datetime",nullable=true)
     */
    private $firstErrorAt=null;

    /**
     * @ORM\Column(name="error_at",type="datetime",nullable=true)
     */
    private $errorAt=null;

    /**
     * @ORM\Column(name="corrected_at",type="datetime", nullable=true)
     */
    private $correctedAt=null;

    /**
     * @ORM\Column(name="priority_updated_at",type="datetime", nullable=true)
     */
    private $priorityUpdatedAt=null;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\ProcedureType
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\ProcedureType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="procedure_type_id_procedure_type", referencedColumnName="id_procedure_type")
     * })
     */
    private $procedureTypeProcedureType;

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Employer
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Employer", inversedBy="realProcedure",  cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employer_id_employer", referencedColumnName="id_employer")
     * })
     */
    private $employerEmployer;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="realProcedureRealProcedure", cascade={"persist"})
     */
    private $action;

    /**
     * @var integer
     * 0 - normal
     * 1 - medium
     * 2 - High
     * @ORM\Column(type="integer")
     */
    private $priority = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\StatusTypes", inversedBy="procedures")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="status_id_procedure",referencedColumnName="id_status_type",nullable=true)
     * })
     */
    private $procedureStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string",nullable=true)
     */
    private $status;

    /**
     * @var integer
     *  0 - false
     *  1 - true
     *  2 - error
     * @ORM\Column(name="max_time_reached", type="integer",nullable=true)
     */
    private $maxTimeReached = 0;

    /**
     * Set idProcedure
     *
     * @param integer $idProcedure
     *
     * @return RealProcedure
     */
    public function setIdProcedure($idProcedure)
    {
        $this->idProcedure = $idProcedure;

        return $this;
    }

    /**
     * Get idProcedure
     *
     * @return integer
     */
    public function getIdProcedure()
    {
        return $this->idProcedure;
    }

    /**
     * Set userUser
     *
     * @param \RocketSeller\TwoPickBundle\Entity\User $userUser
     *
     * @return RealProcedure
     */
    public function setUserUser(\RocketSeller\TwoPickBundle\Entity\User $userUser)
    {
        $this->userUser = $userUser;

        return $this;
    }

    /**
     * Get userUser
     *
     * @return \RocketSeller\TwoPickBundle\Entity\User
     */
    public function getUserUser()
    {
        return $this->userUser;
    }

    /**
     * Set procedureTypeProcedureType
     *
     * @param \RocketSeller\TwoPickBundle\Entity\ProcedureType $procedureTypeProcedureType
     *
     * @return RealProcedure
     */
    public function setProcedureTypeProcedureType(\RocketSeller\TwoPickBundle\Entity\ProcedureType $procedureTypeProcedureType)
    {
        $this->procedureTypeProcedureType = $procedureTypeProcedureType;

        return $this;
    }

    /**
     * Get procedureTypeProcedureType
     *
     * @return \RocketSeller\TwoPickBundle\Entity\ProcedureType
     */
    public function getProcedureTypeProcedureType()
    {
        return $this->procedureTypeProcedureType;
    }

    /**
     * Set employerEmployer
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Employer $employerEmployer
     *
     * @return RealProcedure
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
     * Constructor
     */
    public function __construct()
    {
        $this->action = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add action
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Action $action
     *
     * @return RealProcedure
     */
    public function addAction(\RocketSeller\TwoPickBundle\Entity\Action $action)
    {
        $action->setRealProcedureRealProcedure($this);
        $this->action[] = $action;

        return $this;
    }

    /**
     * Remove action
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Action $action
     */
    public function removeAction(\RocketSeller\TwoPickBundle\Entity\Action $action)
    {
        $this->action->removeElement($action);
    }

    /**
     * Get action
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RealProcedure
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getProcedureTypeProcedureType()->getName();
    }



    /**
     * Set actionChangedAt
     *
     * @param \DateTime $actionChangedAt
     *
     * @return RealProcedure
     */
    public function setActionChangedAt($actionChangedAt)
    {
        $this->actionChangedAt = $actionChangedAt;
        return $this;
    }

    /**
     * Get actionChangedAt
     *
     * @return \DateTime
     */
    public function getActionChangedAt()
    {
        return $this->actionChangedAt;
    }

    /**
     * Set statusUpdatedAt
     *
     * @param \DateTime $statusUpdatedAt
     *
     * @return RealProcedure
     */
    public function setStatusUpdatedAt($statusUpdatedAt)
    {
        $this->statusUpdatedAt = $statusUpdatedAt;

        return $this;
    }

    /**
     * Get statusUpdatedAt
     *
     * @return \DateTime
     */
    public function getStatusUpdatedAt()
    {
        return $this->statusUpdatedAt;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return RealProcedure
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        if($priority == 3){
            $this->maxTimeReached = 1;
        }
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $actionType
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsByActionType($actionType)
    {
        $criteria = Criteria::create()
        ->where(Criteria::expr()->eq('actionTypeActionType',$actionType));
        return $this->action->matching($criteria);
    }

    /**
     * @param $actionType
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsNotMatching($actionType)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('actionTypeActionType',$actionType));
        return $this->action->matching($criteria);
    }

    /**
     * @param $actionStatus
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsByActionStatus($actionStatus)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('actionStatus',$actionStatus));
        return $this->action->matching($criteria);
    }

    /**
     * @param $actionStatus
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsNotMatchingActionStatus($actionStatus)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('actionStatus',$actionStatus));
        return $this->action->matching($criteria);
    }

    /**
     * @param $person
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsByPerson($person)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('personPerson',$person));
        return $this->action->matching($criteria);
    }

    /**
     * @param $employerHasEmployee
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsByEmployerHasEmployee(EmployerHasEmployee $employerHasEmployee)
    {
        $person = $employerHasEmployee->getEmployeeEmployee()->getPersonPerson();
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('personPerson',$person));
        return $this->action->matching($criteria);
    }

    /**
     * @param $employerHasEntity
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionByEmployerHasEntity($employerHasEntity){
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('employerEntity',$employerHasEntity));
        return $this->action->matching($criteria);
    }

    /**
     * @param $employeeHasEntity
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionByEmployeeHasEntity($employeeHasEntity){
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('employeeEntity',$employeeHasEntity));
        return $this->action->matching($criteria);
    }


    /**
     * @param Person $person
     * @param ActionType $actionType
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsByPersonAndActionType(Person $person, ActionType $actionType)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('personPerson',$person))
            ->andWhere(Criteria::expr()->eq('actionTypeActionType',$actionType));
        return $this->action->matching($criteria);
    }


    /**
     * @param string $id
     * @return RocketSellerTwoPickBundle:Action
     */
    public function getActionById($id)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('idAction',intval($id)));
        return $this->action->matching($criteria)->first();
    }


    /**
     * Set backOfficeDate
     *
     * @param \DateTime $backOfficeDate
     *
     * @return RealProcedure
     */
    public function setBackOfficeDate($backOfficeDate)
    {
        $this->backOfficeDate = $backOfficeDate;

        return $this;
    }

    /**
     * Get backOfficeDate
     *
     * @return \DateTime
     */
    public function getBackOfficeDate()
    {
        return $this->backOfficeDate;
    }

    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     *
     * @return RealProcedure
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set procedureStatus
     *
     * @param \RocketSeller\TwoPickBundle\Entity\StatusTypes $procedureStatus
     *
     * @return RealProcedure
     */
    public function setProcedureStatus(\RocketSeller\TwoPickBundle\Entity\StatusTypes $procedureStatus = null)
    {
        $this->status = $procedureStatus->getName();
        $today = new DateTime();
        if($procedureStatus->getCode()=='FIN')
            $this->finishedAt = $today;
        $this->procedureStatus = $procedureStatus;
        return $this;
    }

    /**
     * Get procedureStatus
     *
     * @return \RocketSeller\TwoPickBundle\Entity\StatusTypes
     */
    public function getProcedureStatus()
    {
        return $this->procedureStatus;
    }

    /**
     * Get procedureTypeName
     *
     * @return string
     */
    public function getProcedureTypeName()
    {
        return $this->procedureTypeProcedureType->getName();
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return RealProcedure
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set errorAt
     *
     * @param \DateTime $errorAt
     *
     * @return RealProcedure
     */
    public function setErrorAt($errorAt)
    {
        if($this->firstErrorAt==null)
            $this->firstErrorAt = $errorAt;
        $this->errorAt = $errorAt;
        return $this;
    }

    /**
     * Get errorAt
     *
     * @return \DateTime
     */
    public function getErrorAt()
    {
        return $this->errorAt;
    }

    /**
     * Set correctedAt
     *
     * @param \DateTime $correctedAt
     *
     * @return RealProcedure
     */
    public function setCorrectedAt($correctedAt)
    {
        $this->correctedAt = $correctedAt;

        return $this;
    }

    /**
     * Get correctedAt
     *
     * @return \DateTime
     */
    public function getCorrectedAt()
    {
        return $this->correctedAt;
    }

    /**
     * Set firstErrorAt
     *
     * @param \DateTime $firstErrorAt
     *
     * @return RealProcedure
     */
    public function setFirstErrorAt($firstErrorAt)
    {
        $this->firstErrorAt = $firstErrorAt;

        return $this;
    }

    /**
     * Get firstErrorAt
     *
     * @return \DateTime
     */
    public function getFirstErrorAt()
    {
        return $this->firstErrorAt;
    }

    /**
     * Set maxTimeReached
     *
     * @param integer $maxTimeReached
     *
     * @return RealProcedure
     */
    public function setMaxTimeReached($maxTimeReached)
    {
        $this->maxTimeReached = $maxTimeReached;

        return $this;
    }

    /**
     * Get maxTimeReached
     *
     * @return integer
     */
    public function getMaxTimeReached()
    {
        return $this->maxTimeReached;
    }

    /**
     * Get procedureStatusCode
     *
     * @return string
     */
    public function getProcedureStatusCode()
    {
        return $this->getProcedureStatus()->getCode();
    }

    /**
     * Set priorityUpdatedAt
     *
     * @param \DateTime $priorityUpdatedAt
     *
     * @return RealProcedure
     */
    public function setPriorityUpdatedAt($priorityUpdatedAt)
    {
        $this->priorityUpdatedAt = $priorityUpdatedAt;

        return $this;
    }

    /**
     * Get priorityUpdatedAt
     *
     * @return \DateTime
     */
    public function getPriorityUpdatedAt()
    {
        return $this->priorityUpdatedAt;
    }
}
