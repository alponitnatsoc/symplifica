<?php

namespace RocketSeller\TwoPickBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_user_person1", columns={"person_id_person"})})
 * @ORM\Entity
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    protected $google_id;

    /**
     * @ORM\Column(type="string", length=200, nullable=TRUE)
     */
    protected $linkedin_id;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $google_access_token;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $linkedin_access_token;

    public function __construct()
    {
        parent::__construct();
        $this->date_created = new \DateTime("now");
        //@todo GABRIEL agregar id unico del usuario al momento de registrar.
        $this->code = substr(md5(uniqid(rand(), true)), 0, 8);
        // your own logic
    }

    /**
     * @var \RocketSeller\TwoPickBundle\Entity\Person
     *
     * @ORM\OneToOne(targetEntity="RocketSeller\TwoPickBundle\Entity\Person",cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id_person", referencedColumnName="id_person")
     * })
     */
    private $personPerson;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="userUser", cascade={"persist"})
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity="RealProcedure", mappedBy="userUser", cascade={"persist"})
     */
    private $realProcedure;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrders", mappedBy="idUser", cascade={"persist"})
     */
    private $purchaseOrders;

    /**
     * @ORM\OneToMany(targetEntity="Pay", mappedBy="userIdUser", cascade={"persist"})
     */
    private $payments;

    /**
     * Columna utilizada para conocer el estado de la suscripcion del usuario
     *
     * Estados del usuario:
     *      0 - Inactivo / Suscripcion desactivada o inactiva
     *      1 - Mail confirmado
     *      2 - Registro completado
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint")
     */
    private $status = 1;

    /**
     * Columna utilizada para saber cantidad de meses gratis
     *
     * Estados del usuario:
     *      0 - sin tiempo gratis
     *      1 - 1 mes gratis
     *      n - n meses gratis
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint")
     */
    private $isFree = 1;

    /**
     * Columna utilizada para conocer el estado del empleado
     * 0 No ha iiciado labores
     * 1 ya inicio labores
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint")
     */
    private $legalFlag = 0;

    /**
     * Columna utilizada para saber si el usuario requiere registro express
     * 0 false
     * 1 true
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint")
     */
    private $express = 0;

    /**
     * Columna utilizada para conocer el estado de la suscripcion del usuario
     * 0 Actualmente Sin pagar Symplifica
     * 1 Pagando Symplifica
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint")
     */
    private $paymentState = 0;

    /**
     *
     * @var SmallIntType
     *
     * @ORM\Column(type="smallint",nullable=true)
     */
    private $dayToPay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $date_created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastPayDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isFreeTo;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    protected $code;

    /**
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="userId", cascade={"persist"})
     */
    private $invitations;

    /**
     * @ORM\OneToMany(targetEntity="Referred", mappedBy="userId", cascade={"persist"})
     */
    private $referrals;

    /**
     * Set personPerson
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Person $personPerson
     *
     * @return User
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
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set linkedinId
     *
     * @param string $linkedinId
     *
     * @return User
     */
    public function setLinkedinId($linkedinId)
    {
        $this->linkedin_id = $linkedinId;

        return $this;
    }

    /**
     * Get linkedinId
     *
     * @return string
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set linkedinAccessToken
     *
     * @param string $linkedinAccessToken
     *
     * @return User
     */
    public function setLinkedinAccessToken($linkedinAccessToken)
    {
        $this->linkedin_access_token = $linkedinAccessToken;

        return $this;
    }

    /**
     * Get linkedinAccessToken
     *
     * @return string
     */
    public function getLinkedinAccessToken()
    {
        return $this->linkedin_access_token;
    }

    /**
     * Add action
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Action $action
     *
     * @return User
     */
    public function addAction(\RocketSeller\TwoPickBundle\Entity\Action $action)
    {
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
     * Add realProcedure
     *
     * @param \RocketSeller\TwoPickBundle\Entity\RealProcedure $realProcedure
     *
     * @return User
     */
    public function addRealProcedure(\RocketSeller\TwoPickBundle\Entity\RealProcedure $realProcedure)
    {
        $this->realProcedure[] = $realProcedure;

        return $this;
    }

    /**
     * Remove realProcedure
     *
     * @param \RocketSeller\TwoPickBundle\Entity\RealProcedure $realProcedure
     */
    public function removeRealProcedure(\RocketSeller\TwoPickBundle\Entity\RealProcedure $realProcedure)
    {
        $this->realProcedure->removeElement($realProcedure);
    }

    /**
     * Get realProcedure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRealProcedure()
    {
        return $this->realProcedure;
    }

    /**
     * Add purchaseOrder
     *
     * @param \RocketSeller\TwoPickBundle\Entity\PurchaseOrders $purchaseOrder
     *
     * @return User
     */
    public function addPurchaseOrder(\RocketSeller\TwoPickBundle\Entity\PurchaseOrders $purchaseOrder)
    {
        $this->purchaseOrders[] = $purchaseOrder;

        return $this;
    }

    /**
     * Remove purchaseOrder
     *
     * @param \RocketSeller\TwoPickBundle\Entity\PurchaseOrders $purchaseOrder
     */
    public function removePurchaseOrder(\RocketSeller\TwoPickBundle\Entity\PurchaseOrders $purchaseOrder)
    {
        $this->purchaseOrders->removeElement($purchaseOrder);
    }

    /**
     * Get purchaseOrders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPurchaseOrders()
    {
        return $this->purchaseOrders;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return User
     * Estados del usuario:
     *      0 - Inactivo / Suscripcion desactivada o inactiva
     *      1 - Mail confirmado
     *      2 - Registro completado
     *
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add payment
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Pay $payment
     *
     * @return User
     */
    public function addPayment(\RocketSeller\TwoPickBundle\Entity\Pay $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Pay $payment
     */
    public function removePayment(\RocketSeller\TwoPickBundle\Entity\Pay $payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return User
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add invitation
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Invitation $invitation
     *
     * @return User
     */
    public function addInvitation(\RocketSeller\TwoPickBundle\Entity\Invitation $invitation)
    {
        $this->invitations[] = $invitation;

        return $this;
    }

    /**
     * Remove invitation
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Invitation $invitation
     */
    public function removeInvitation(\RocketSeller\TwoPickBundle\Entity\Invitation $invitation)
    {
        $this->invitations->removeElement($invitation);
    }

    /**
     * Get invitations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * Add referral
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Referred $referral
     *
     * @return User
     */
    public function addReferral(\RocketSeller\TwoPickBundle\Entity\Referred $referral)
    {
        $this->referrals[] = $referral;

        return $this;
    }

    /**
     * Remove referral
     *
     * @param \RocketSeller\TwoPickBundle\Entity\Referred $referral
     */
    public function removeReferral(\RocketSeller\TwoPickBundle\Entity\Referred $referral)
    {
        $this->referrals->removeElement($referral);
    }

    /**
     * Get referrals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferrals()
    {
        return $this->referrals;
    }

    /**
     * Set dayToPay
     *
     * @param integer $dayToPay
     *
     * @return User
     */
    public function setDayToPay($dayToPay)
    {
        $this->dayToPay = $dayToPay;

        return $this;
    }

    /**
     * Get dayToPay
     *
     * @return integer
     */
    public function getDayToPay()
    {
        return $this->dayToPay;
    }

    /**
     * Set paymentState
     *
     * @param integer $paymentState
     *
     * @return User
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    /**
     * Get paymentState
     *
     * @return integer
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * Set express
     *
     * @param integer $express
     *
     * @return User
     */
    public function setExpress($express)
    {
        $this->express = $express;

        return $this;
    }

    /**
     * Get express
     *
     * @return integer
     */
    public function getExpress()
    {
        return $this->express;
    }

    /**
     * Set lastPayDate
     *
     * @param \DateTime $lastPayDate
     *
     * @return User
     */
    public function setLastPayDate($lastPayDate)
    {
        $this->lastPayDate = $lastPayDate;

        return $this;
    }

    /**
     * Get lastPayDate
     *
     * @return \DateTime
     */
    public function getLastPayDate()
    {
        return $this->lastPayDate;
    }

    /**
     * Set legalFlag
     *
     * @param integer $legalFlag
     *
     * @return User
     */
    public function setLegalFlag($legalFlag)
    {
        $this->legalFlag = $legalFlag;

        return $this;
    }

    /**
     * Get legalFlag
     *
     * @return integer
     */
    public function getLegalFlag()
    {
        return $this->legalFlag;
    }


    /**
     * Set isFree
     *
     * @param integer $isFree
     *
     * @return User
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return integer
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set isFreeTo
     *
     * @param \DateTime $isFreeTo
     *
     * @return User
     */
    public function setIsFreeTo($isFreeTo)
    {
        $this->isFreeTo = $isFreeTo;

        return $this;
    }

    /**
     * Get isFreeTo
     *
     * @return \DateTime
     */
    public function getIsFreeTo()
    {
        return $this->isFreeTo;
    }
}
