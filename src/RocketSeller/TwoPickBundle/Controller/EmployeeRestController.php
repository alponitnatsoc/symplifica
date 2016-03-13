<?php

/**
 * Created by PhpStorm.
 * User: gabrielsamoma
 * Date: 11/17/15
 * Time: 10:43 AM
 */

namespace RocketSeller\TwoPickBundle\Controller;

use GuzzleHttp\Psr7\Response;
use RocketSeller\TwoPickBundle\Admin\ContractHasBenefitsAdmin;
use RocketSeller\TwoPickBundle\Entity\AccountType;
use RocketSeller\TwoPickBundle\Entity\Bank;
use RocketSeller\TwoPickBundle\Entity\Benefits;
use RocketSeller\TwoPickBundle\Entity\Contract;
use RocketSeller\TwoPickBundle\Entity\ContractHasBenefits;
use RocketSeller\TwoPickBundle\Entity\ContractHasWorkplace;
use RocketSeller\TwoPickBundle\Entity\ContractType;
use RocketSeller\TwoPickBundle\Entity\Employee;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use RocketSeller\TwoPickBundle\Entity\EmployeeHasEntity;
use RocketSeller\TwoPickBundle\Entity\Employer;
use RocketSeller\TwoPickBundle\Entity\EmployerHasEmployee;
use RocketSeller\TwoPickBundle\Entity\EmployerHasEntity;
use RocketSeller\TwoPickBundle\Entity\Entity;
use RocketSeller\TwoPickBundle\Entity\Frequency;
use RocketSeller\TwoPickBundle\Entity\Novelty;
use RocketSeller\TwoPickBundle\Entity\PayMethod;
use RocketSeller\TwoPickBundle\Entity\Payroll;
use RocketSeller\TwoPickBundle\Entity\PayType;
use RocketSeller\TwoPickBundle\Entity\Person;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RocketSeller\TwoPickBundle\Entity\Phone;
use RocketSeller\TwoPickBundle\Entity\User;
use RocketSeller\TwoPickBundle\Entity\WeekWorkableDays;
use RocketSeller\TwoPickBundle\Entity\Workplace;
use Symfony\Component\Validator\ConstraintViolationList;
use RocketSeller\TwoPickBundle\Entity\Notification;
use DateTime;

class EmployeeRestController extends FOSRestController
{

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when any Id does not exist in the DB"
     *   }
     * )
     *
     * @param integer $idEmployerHasEmployee
     *
     *
     * @RequestParam(name="$idEmployerHasEmployee", nullable=false, strict=true, description="workplace department.")
     *
     * @return View
     */
    public function getLiquidatePayrollAction($idEmployerHasEmployee)
    {
        $em = $this->getDoctrine()->getManager();
        $repoEmployee = $em->getRepository("RocketSellerTwoPickBundle:EmployerHasEmployee");
        /** @var EmployerHasEmployee $realEmployerHasEmployee */
        $realEmployerHasEmployee = $repoEmployee->find($idEmployerHasEmployee);
        $view = View::create();

        $executionType = "D";
        $request = $this->container->get('request');
        $request->setMethod("POST");
        $request->request->add(array(
            "employee_id" => $idEmployerHasEmployee,
            "execution_type" => $executionType,
        ));
        $insertionAnswer = $this->forward('RocketSellerTwoPickBundle:PayrollRest:postExecutePayrollLiquidation', array('_format' => 'json'));
        if ($insertionAnswer->getStatusCode() != 200) {
            return $view->setStatusCode($insertionAnswer->getStatusCode());
        }

        $contracts = $realEmployerHasEmployee->getContracts();
        $actContract = null;
        /** @var Contract $cont */
        foreach ($contracts as $cont) {
            if ($cont->getState() == 1) {
                $actContract = $cont;
                break;
            }
        }
        $methodToCall = "postExecutePayrollLiquidation";
        $novelties = $actContract->getActivePayroll()->getNovelties();
        /** @var Novelty $nov */
        foreach ($novelties as $nov) {
            if ($nov->getNoveltyTypeNoveltyType()->getPayrollCode() == 145 || $nov->getNoveltyTypeNoveltyType()->getPayrollCode() == 150) {
                $methodToCall = "postExecuteVacationLiquidation";
            }
        }

        $executionType = "P";
        $request->setMethod("POST");
        $request->request->add(array(
            "employee_id" => $idEmployerHasEmployee,
            "execution_type" => $executionType,
        ));
        $insertionAnswer = $this->forward('RocketSellerTwoPickBundle:PayrollRest:' . $methodToCall, array('_format' => 'json'));

        if ($insertionAnswer->getStatusCode() != 200) {
            return $view->setStatusCode($insertionAnswer->getStatusCode());
        }

        $request->setMethod("GET");
        $insertionAnswer = $this->forward('RocketSellerTwoPickBundle:PayrollRest:getGeneralPayroll', array(
            "employeeId" => $idEmployerHasEmployee,
        ), array('_format' => 'json'));
        if ($insertionAnswer->getStatusCode() != 200) {
            return $view->setStatusCode($insertionAnswer->getStatusCode());
        }
        return $view->setStatusCode(200)->setData(json_decode($insertionAnswer->getContent(), true));
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when any Id does not exist in the DB"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     *
     * @RequestParam(name="payTypeId", nullable=false, strict=true, description="workplace department.")
     * @RequestParam(name="bankId", nullable=true, strict=true, description="workplace department.")
     * @RequestParam(name="accountTypeId", nullable=true, strict=true, description="workplace department.")
     * @RequestParam(name="frequencyId", nullable=true, strict=true, description="workplace department.")
     * @RequestParam(name="accountNumber", nullable=true, strict=true, description="workplace department.")
     * @RequestParam(name="cellphone", nullable=true, strict=true, description="workplace department.")
     * @RequestParam(name="contractId", nullable=false, strict=true, description="id of the contract.")
     * @RequestParam(name="idEmployer", nullable=false, strict=true, description="id of the contract.")
     * @RequestParam(array=true, name="register_social_security", nullable=true, strict=true, description="afiliaciones")
     *
     * @return View
     */
    public function postNewEmployeeSubmitAction(ParamFetcher $paramFetcher)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $view = View::create();
            return $view->setStatusCode(401)->setData(array("error" => array("login" => "El usuario no está logeado"), "url" => $this->generateUrl("fos_user_security_login")));
        }
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        /** @var Employee $employee */
        $employee = null;
//        $idContract = $paramFetcher->get("register_social_security");
//        $idEmployer = $idContract['idEmployer'];
        $idContract = $paramFetcher->get("contractId");
        $view = View::create();

        //search the contract
        $contractRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Contract');
        /** @var Contract $contract */
        $contract = $contractRepo->find($idContract);
        if ($contract == null) {
            //TODO return him to steps 2,3
            $view->setStatusCode(403)->setData(array("error" => array('contract' => "You don't have that contract")));
            return $view;
        }
        if ($user->getPersonPerson()->getEmployer()->getIdEmployer() != $contract->getEmployerHasEmployeeEmployerHasEmployee()->getEmployerEmployer()->getIdEmployer()) {
            //TODO return him to step 2
            $view->setStatusCode(403)->setData(array(
                "error" => array('contract' => "You don't have that contract"),
                "ulr" => ""));
            return $view;
        }
        //payMethod repos
        $bankRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Bank');
        $accountTypeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:AccountType');
        $payTypeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:PayType');
        $frequencyRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Frequency');
        $payMethod = $contract->getPayMethodPayMethod();
        if ($payMethod != null) {
            $payMethod->setCellPhone("");
            $payMethod->setAccountNumber("");
            $payMethod->setBankBank(null);
            $payMethod->setAccountTypeAccountType(null);
            $payMethod->setFrequencyFrequency(null);
            $payMethod->setPayTypePayType(null);
        } else {
            $payMethod = new PayMethod();
        }
        //Now for the payType and Pay Method
        //TODO check if valid??
        $payMethod->setAccountNumber($paramFetcher->get('accountNumber'));
        //TODO check if vaild??
        $payMethod->setCellPhone($paramFetcher->get('cellphone'));
        $payMethod->setUserUser($user);

        //check if the Pay Method Ids are valid: Bank payType and AccountType

        if ($paramFetcher->get('bankId')) {
            /** @var Bank $tempBank */
            $tempBank = $bankRepo->find($paramFetcher->get('bankId'));
            if ($tempBank == null) {
                $view->setStatusCode(404)->setHeader("error", "The bankId ID " . $paramFetcher->get('bankId') . " is invalid");
                return $view;
            }
            $payMethod->setBankBank($tempBank);
        }


        if ($paramFetcher->get('payTypeId')) {
            /** @var PayType $tempPayType */
            $tempPayType = $payTypeRepo->find($paramFetcher->get('payTypeId'));
            if ($tempPayType == null) {
                $view->setStatusCode(404)->setHeader("error", "The payTypeId ID " . $paramFetcher->get('payTypeId') . " is invalid");
                return $view;
            }
            $payMethod->setPayTypePayType($tempPayType);
        }

        if ($paramFetcher->get('accountTypeId')) {
            /** @var AccountType $tempAccountType */
            $tempAccountType = $accountTypeRepo->find($paramFetcher->get('accountTypeId'));
            if ($tempAccountType == null) {
                $view->setStatusCode(404)->setHeader("error", "The accountTypeId ID " . $paramFetcher->get('accountTypeId') . " is invalid");
                return $view;
            }
            $payMethod->setAccountTypeAccountType($tempAccountType);
        }

        if ($paramFetcher->get('frequencyId')) {
            /** @var Frequency $tempFrequency */
            $tempFrequency = $frequencyRepo->find($paramFetcher->get('frequencyId'));
            if ($tempFrequency == null) {
                $view->setStatusCode(404)->setHeader("error", "The frequencyId ID " . $paramFetcher->get('frequencyId') . " is invalid");
                return $view;
            }
            $payMethod->setFrequencyFrequency($tempFrequency);
        }

        // add the user to pay
        // URL used for test porpouses, the line above should be used in production.
        $url_request = "http://localhost:8002/api/public/v1/clients";
        $userPerson = $user->getPersonPerson();
        $parameters = array(
            'documentType' => $userPerson->getDocumentType(),
            'documentNumber' => $userPerson->getDocument(),
            'name' => $userPerson->getNames(),
            'lastName' => $userPerson->getLastName1() . " " . $userPerson->getLastName2(),
            'year' => $userPerson->getBirthDate()->format("Y"),
            'month' => $userPerson->getBirthDate()->format("m"),
            'day' => $userPerson->getBirthDate()->format("d"),
            'phone' => $userPerson->getPhones()->get(0)->getPhoneNumber(),
            'email' => $user->getEmail(),
        );
        $options = array(
            'json' => $parameters,
        );
//        /** @var Response $response */
//        $response = $this->get('guzzle.client.api_rest')->post($url_request, $options);
//        if($response->getStatusCode()!=201){
//            $view->setData(array('error'=>array('credit card'=>'something is wrong with the information')))->setStatusCode($response->getStatusCode());
//            return $view;
//        }
//        //finally add the pay method to the contract and add the contract to the EmployerHasEmployee
        // relation that is been created
        //if the CC data is null then add notification to add it

        $contract->setPayMethodPayMethod($payMethod);


        //Final Entity Validation
        $errors = $this->get('validator')->validate($contract, array('Update'));

        if (count($errors) == 0) {
            $employee = $contract->getEmployerHasEmployeeEmployerHasEmployee()->getEmployeeEmployee();
            if ($employee->getRegisterState() == 75) {
                $employee->setRegisterState(100);
            }
            $em->persist($contract);
            $em->flush();
            //$idContract id del contrato que se esta creando o editando, true para eliminar payroll existentes y dejar solo el nuevo
            $data = $this->forward('RocketSellerTwoPickBundle:Payroll:createPayrollToContract', array(
                'idContract' => $contract->getIdContract(),
                'deleteActivePayroll' => true,
                'period' => null,
                'month' => null,
                'year' => null
            ));
            $view->setData(array('url' => $this->generateUrl('show_dashboard')))->setStatusCode(200);
            //$idContract id del contrato que se esta creando o editando, true para eliminar payroll existentes y dejar solo el nuevo
            $data = $this->forward('RocketSellerTwoPickBundle:Payroll:createPayrollToContract', array(
                'idContract' => $contract->getIdContract(),
                'deleteActivePayroll' => true,
                'period' => null,
                'month' => null,
                'year' => null
            ));
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="documentType", nullable=false, strict=true, description="documentType.")
     * @RequestParam(name="document", nullable=false, strict=true, description="document.")
     * @RequestParam(name="names", nullable=false,  strict=true, description="names.")
     * @RequestParam(name="lastName1", nullable=false,  strict=true, description="last Name 1.")
     * @RequestParam(name="lastName2", nullable=false,  strict=true, description="last Name 2.")
     * @RequestParam(name="year", nullable=false, strict=true, description="year of birth.")
     * @RequestParam(name="month", nullable=false, strict=true, description="month of birth.")
     * @RequestParam(name="day", nullable=false, strict=true, description="day of birth.")
     * @RequestParam(name="birthCountry", nullable=false, strict=true, description="Country where the employee was birth.")
     * @RequestParam(name="birthDepartment", nullable=false, strict=true, description="Department where the employee was birth.")
     * @RequestParam(name="birthCity", nullable=false, strict=true, description="city where the employee was birth.")
     * @RequestParam(name="civilStatus", nullable=false, strict=true, description="the civil status of the employee")
     * @RequestParam(name="gender", nullable=false, strict=true, description="the gender of the employee")
     * @RequestParam(name="documentExpeditionDateYear", nullable=false, strict=true, description="document expedition year")
     * @RequestParam(name="documentExpeditionDateMonth", nullable=false, strict=true, description="document expedition month")
     * @RequestParam(name="documentExpeditionDateDay", nullable=false, strict=true, description="document expedition day")
     * @RequestParam(name="documentExpeditionPlace", nullable=false, strict=true, description="the gender of the employee")
     * @RequestParam(name="employeeId", nullable=false, strict=true, description="id if exist else -1.")
     *
     * @return View
     */
    public function postNewEmployeeSubmitStep1Action(ParamFetcher $paramFetcher)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $view = View::create();
            return $view->setStatusCode(401)->setData(array("error" => array("login" => "El usuario no está logeado"), "url" => $this->generateUrl("fos_user_security_login")));
        }
        $user = $this->getUser();
        /** @var Person $people */
        $people = $user->getPersonPerson();
        $employer = $people->getEmployer();
        if ($employer == null) {
            //TODO return user to step 1
        }

        //all the data is valid
        if (true) {
            /** @var Employee $employee */
            $employee = null;
            $id = $paramFetcher->get("employeeId");
            /** @var EmployerHasEmployee $employerEmployee */
            $employerEmployee = null;
            $view = View::create();
            if ($id == -1) {
                $employee = new Employee();
                $person = new Person();
                $employee->setPersonPerson($person);
                $employerEmployee = new EmployerHasEmployee();
                $employerEmployee->setEmployeeEmployee($employee);
                $employerEmployee->setEmployerEmployer($user->getPersonPerson()->getEmployer());
                $employee->addEmployeeHasEmployer($employerEmployee);
            }elseif ($id == -2) {
                $employee = new Employee();
                $person = $people;
                $employee->setPersonPerson($person);
                $employerEmployee = new EmployerHasEmployee();
                $employerEmployee->setEmployeeEmployee($employee);
                $employerEmployee->setEmployerEmployer($user->getPersonPerson()->getEmployer());
                $employee->addEmployeeHasEmployer($employerEmployee);
            } else {
                $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
                $employee = $repository->find($id);
                //verify if the Id exists or it belongs to the logged user
                if ($employee == null) {
                    $view->setStatusCode(404)->setHeader("error", "The Employee ID " . $paramFetcher->get('employeeId') . " is invalid");
                    $view->setData(array('url' => '/dashboard'));
                    return $view;
                }
                $idEmployer = $user->getPersonPerson()->getEmployer()->getIdEmployer();
                $flag = false;
                /** @var EmployerHasEmployee $ee */
                foreach ($employee->getEmployeeHasEmployers() as $ee) {
                    if ($ee->getEmployerEmployer()->getIdEmployer() == $idEmployer) {
                        $employerEmployee = $ee;
                        $flag = true;
                        break;
                    }
                }
//              @todo Gabriel revisar porque está generando entradas duplicadas en la BD en employer_has_employee

                if (!$flag && ($employee->getPersonPerson()->getDocumentType() == $paramFetcher->get('documentType') && $employee->getPersonPerson()->getDocument() == $paramFetcher->get('document'))) {
                    $flag = true;
                    $employerEmployee = new EmployerHasEmployee();
                    $employerEmployee->setEmployeeEmployee($employee);
                    $employerEmployee->setEmployerEmployer($user->getPersonPerson()->getEmployer());
                    $employee->addEmployeeHasEmployer($employerEmployee);
                }
                if (!$flag) {
                    $view->setStatusCode(403)->setHeader("error", "Not your Employee");
                    $view->setData(array('url' => '/dashboard'));
                    return $view;
                }
            }
            /** @var Person $person */
            $person = $employee->getPersonPerson();
            $person->setNames($paramFetcher->get('names'));
            $person->setLastName1($paramFetcher->get('lastName1'));
            $person->setLastName2($paramFetcher->get('lastName2'));
            $person->setDocument($paramFetcher->get('document'));
            $person->setDocumentType($paramFetcher->get('documentType'));
            $person->setCivilStatus($paramFetcher->get('civilStatus'));
            $person->setGender($paramFetcher->get('gender'));
            $datetime = new DateTime();
            $datetime->setDate($paramFetcher->get('year'), $paramFetcher->get('month'), $paramFetcher->get('day'));
            // TODO validate Date
            $person->setBirthDate($datetime);
            $datetimeDocument = new DateTime();
            $datetimeDocument->setDate($paramFetcher->get('documentExpeditionDateYear'), $paramFetcher->get('documentExpeditionDateMonth'), $paramFetcher->get('documentExpeditionDateDay'));
            $person->setDocumentExpeditionDate($datetimeDocument);
            $person->setDocumentExpeditionPlace($paramFetcher->get('documentExpeditionPlace'));

            //birth repos
            $cityRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:City');
            $depRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Department');
            $conRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Country');

            $tempBCity = $cityRepo->find($paramFetcher->get('birthCity'));
//             @todo No es un campo obligatorio para el registro
//             if ($tempBCity == null) {
//                 $view->setStatusCode(404)->setHeader("error", "The city ID " . $paramFetcher->get('birthCity') . " is invalid");
//                 return $view;
//             }
            $person->setBirthCity($tempBCity);
            $tempBDep = $depRepo->find($paramFetcher->get('birthDepartment'));
//             @todo No es un campo obligatorio para el registro
//             if ($tempBDep == null) {
//                 $view->setStatusCode(404)->setHeader("error", "The department ID " . $paramFetcher->get('birthDepartment') . " is invalid");
//                 return $view;
//             }
            $person->setBirthDepartment($tempBDep);
            $tempBCou = $conRepo->find($paramFetcher->get('birthCountry'));
//             @todo No es un campo obligatorio para el registro
//             if ($tempBCou == null) {
//                 $view->setStatusCode(404)->setHeader("error", "The country ID " . $paramFetcher->get('birthCountry') . " is invalid");
//                 return $view;
//             }
            $person->setBirthCountry($tempBCou);
            $em = $this->getDoctrine()->getManager();
            $errors = $this->get('validator')->validate($employee, array('Update'));
            if (count($errors) == 0) {
                if ($employee->getRegisterState() == 0) {
                    $employee->setRegisterState(25);
                }
                $em->persist($employee);
                $em->flush();
                $view->setData(array('response' => array('idEmployee' => $employee->getIdEmployee())))->setStatusCode(200);
                return $view;
            } else {
                $view = $this->getErrorsView($errors);
                return $view;
            }
        }
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the requested Ids don't exist"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="mainAddress", nullable=false, strict=true, description="mainAddress.")
     * @RequestParam(array=true, name="phonesIds", nullable=false, strict=true, description="id if exist else -1.")
     * @RequestParam(array=true, name="phones", nullable=false, strict=true, description="main workplace Address.")
     * @RequestParam(name="department", nullable=false, strict=true, description="department.")
     * @RequestParam(name="city", nullable=false, strict=true, description="city.")
     * @RequestParam(name="employeeId", nullable=false, strict=true, description="id if exist else -1.")
     * @RequestParam(name="email", nullable=false, strict=true, description="workplace city.")
     * @return View
     */
    public function postNewEmployeeSubmitStep2Action(ParamFetcher $paramFetcher)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $view = View::create();
            return $view->setStatusCode(401)->setData(array("error" => array("login" => "El usuario no está logeado"), "url" => $this->generateUrl("fos_user_security_login")));
        }
        $user = $this->getUser();
        /** @var Person $person */
        $person = $user->getPersonPerson();
        $employer = $person->getEmployer();
        if ($employer == null) {
            //TODO Return user to step 1
        }

        //all the data is valid
        if (true) {
            /** @var Employee $employee */
            $employee = null;
            $id = $paramFetcher->get("employeeId");
            /** @var EmployerHasEmployee $employerEmployee */
            $employerEmployee = null;
            $view = View::create();
            if ($id == -1) {
                //TODO Return user to step 2,1
            } else {
                $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
                $employee = $repository->find($id);
                //verify if the Id exists or it belongs to the logged user
                $idEmployer = $user->getPersonPerson()->getEmployer()->getIdEmployer();
                $flag = false;
                /** @var EmployerHasEmployee $ee */
                foreach ($employee->getEmployeeHasEmployers() as $ee) {
                    if ($ee->getEmployerEmployer()->getIdEmployer() == $idEmployer) {
                        $employerEmployee = $ee;
                        $flag = true;
                        break;
                    }
                }
                if ($employee == null || !$flag) {
                    $employeesData = $user->getPersonPerson()->getEmployer()->getEmployerHasEmployees();
                    return $this->render(
                        'RocketSellerTwoPickBundle:Employee:employeeManager.html.twig', array(
                        'employees' => $employeesData));
                }
            }
            $people = $employerEmployee->getEmployeeEmployee()->getPersonPerson();
            $people->setMainAddress($paramFetcher->get('mainAddress'));
            $people->setEmail($paramFetcher->get('email'));
            $phoneRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Phone');
            $cityRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:City');
            $depRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Department');
            $actualPhonesId = $paramFetcher->get('phonesIds');
            $actualPhonesAdd = $paramFetcher->get('phones');
            $tempCity = $cityRepo->find($paramFetcher->get('city'));
            if ($tempCity == null) {
                $view->setStatusCode(404)->setHeader("error", "The city ID " . $paramFetcher->get('city') . " is invalid");
                return $view;
            }
            $people->setCity($tempCity);
            $tempDep = $depRepo->find($paramFetcher->get('department'));
            if ($tempDep == null) {
                $view->setStatusCode(404)->setHeader("error", "The department ID " . $paramFetcher->get('department') . " is invalid");
                return $view;
            }
            $people->setDepartment($tempDep);
            $em = $this->getDoctrine()->getManager();

            $actualPhones = new ArrayCollection();
            for ($i = 0; $i < count($actualPhonesAdd); $i++) {
                $tempPhone = null;
                if ($actualPhonesId[$i] != "") {
                    /** @var Phone $tempPhone */
                    $tempPhone = $phoneRepo->find($actualPhonesId[$i]);
                    if ($tempPhone->getPersonPerson()->getEmployee()->getIdEmployee() != $employee->getIdEmployee()) {
                        $view = View::create()->setData(array('url' => $this->generateUrl('edit_profile', array('step' => '1')),
                            'error' => array('wokplaces' => 'you dont have those phones')))->setStatusCode(400);
                        return $view;
                    }
                } else {
                    $tempPhone = new Phone();
                }
                $tempPhone->setPhoneNumber($actualPhonesAdd[$i]);
                $actualPhones->add($tempPhone);
            }
            $phones = $people->getPhones();
            /** @var Phone $phone */
            foreach ($phones as $phone) {
                /** @var Phone $actPhone */
                $flag = false;
                foreach ($actualPhones as $actPhone) {
                    if ($phone->getIdPhone() == $actPhone->getIdPhone()) {
                        $flag = true;
                        $phone = $actPhone;
                        $actualPhones->removeElement($actPhone);
                        continue;
                    }
                }
                if (!$flag) {
                    $phone->setPersonPerson(null);
                    $em->persist($phone);
                    $em->remove($phone);
                    $em->flush();
                    $phones->removeElement($phone);
                }
            }
            foreach ($actualPhones as $phone) {
                $people->addPhone($phone);
            }

            $view = View::create();
            $errors = $this->get('validator')->validate($user, array('Update'));

            if (count($errors) == 0) {
                if ($employee->getRegisterState() == 25) {
                    $employee->setRegisterState(50);
                }
                $em->persist($employee);
                $em->flush();
                $view->setStatusCode(200);
                return $view;
            } else {
                $view = $this->getErrorsView($errors);
                return $view;
            }
        }
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the requested Ids don't exist"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="contractType", nullable=false, strict=true, description="contract type.")
     * @RequestParam(name="timeCommitment", nullable=false, strict=true, description="ammount of time in the job.")
     * @RequestParam(name="position", nullable=false, strict=true, description="labors taht are going to be performed.")
     * @RequestParam(name="salary", nullable=true, strict=true, description="ammount of money gettig paid.")
     * @RequestParam(name="salaryD", nullable=true, strict=true, description="ammount of money gettig paid daily.")
     * @RequestParam(array=true, name="idsBenefits", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="benefType", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="amountBenefits", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="periodicityBenefits", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(name="idWorkplace", nullable=false, strict=true, description="place of work.")
     * @RequestParam(name="transportAid", nullable=true, strict=true, description="aid for the employee to transport.")
     * @RequestParam(name="sisben", nullable=true, strict=true, description="employee belongs to SISBEN.")
     * @RequestParam(name="benefitsConditions", nullable=true, strict=true, description="benefits conditions.")
     * @RequestParam(name="startDate", nullable=false, strict=true, description="benefits conditions.")
     * @RequestParam(name="endDate", nullable=true, strict=true, description="benefits conditions.")
     * @RequestParam(name="workableDaysMonth", nullable=true, strict=true, description="benefits conditions.")
     * @RequestParam(array=true, name="workTimeStart", nullable=true, strict=true, description="benefits conditions.")
     * @RequestParam(array=true, name="workTimeEnd", nullable=true, strict=true, description="benefits conditions.")
     * @RequestParam(name="weekWorkableDays", nullable=true, strict=true, description="days the employee will work per week.")
     * @RequestParam(array=true,name="weekDays", nullable=true, strict=true, description="days the employee will work per week.")
     * @RequestParam(name="employeeId", nullable=false, strict=true, description="id if exist else -1.")
     * @RequestParam(name="contractId", nullable=true, strict=true, description="id of the contract.")
     * @return View
     */
    public function postNewEmployeeSubmitStep3Action(ParamFetcher $paramFetcher)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $view = View::create();
            return $view->setStatusCode(401)->setData(array("error" => array("login" => "El usuario no está logeado"), "url" => $this->generateUrl("fos_user_security_login")));
        }
        $user = $this->getUser();
        /** @var Person $person */
        $person = $user->getPersonPerson();
        $employer = $person->getEmployer();
        if ($employer == null) {
            //TODO Return user to step 1
        }

        //all the data is valid
        if (true) {
            /** @var Employee $employee */
            $employee = null;
            $id = $paramFetcher->get("employeeId");
            /** @var EmployerHasEmployee $employerEmployee */
            $employerEmployee = null;
            $view = View::create();
            if ($id == -1) {
                //TODO Return user to step 2,1
            } else {
                $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
                $employee = $repository->find($id);
                //verify if the Id exists or it belongs to the logged user
                $idEmployer = $user->getPersonPerson()->getEmployer()->getIdEmployer();
                $flag = false;
                /** @var EmployerHasEmployee $ee */
                foreach ($employee->getEmployeeHasEmployers() as $ee) {
                    if ($ee->getEmployerEmployer()->getIdEmployer() == $idEmployer) {
                        $employerEmployee = $ee;
                        $flag = true;
                        break;
                    }
                }
                if ($employee == null || !$flag) {
                    $employeesData = $user->getPersonPerson()->getEmployer()->getEmployerHasEmployees();
                    return $this->render(
                        'RocketSellerTwoPickBundle:Employee:employeeManager.html.twig', array(
                        'employees' => $employeesData));
                }
            }
            $idContract = $paramFetcher->get("contractId");
            //search the contract
            $contractRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Contract');
            /** @var Contract $contract */
            $contract = $contractRepo->find($idContract);
            if ($contract == null) {
                //Create the contract
                $contract = new Contract();
                $contract->setState(1);
                $employerEmployee->addContract($contract);
            }
            if ($user->getPersonPerson()->getEmployer()->getIdEmployer() != $contract->getEmployerHasEmployeeEmployerHasEmployee()->getEmployerEmployer()->getIdEmployer()) {
                //TODO return him to step 2
                $view->setStatusCode(403)->setData(array(
                    "error" => array('contract' => "You don't have that contract"),
                    "ulr" => ""));
                return $view;
            }


            //contract repos
            $contractTypeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:ContractType');
            //$employeeContractTypeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:EmployeeContractType');
            $timeCommitmentRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:TimeCommitment');
            $positionRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Position');
            $workplaceRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Workplace');
            $benefitRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Benefits');
            $contracHasBenefitRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:ContractHasBenefits');

            $em = $this->getDoctrine()->getManager();

            /** @var ContractType $tempContractType */
            $tempContractType = $contractTypeRepo->find($paramFetcher->get('contractType'));
            if ($tempContractType == null) {
                $view->setStatusCode(404)->setHeader("error", "The contractType ID " . $paramFetcher->get('contractType') . " is invalid");
                return $view;
            }
            $contract->setContractTypeContractType($tempContractType);

            /* $tempEmployeeContractType = $employeeContractTypeRepo->find($paramFetcher->get('employeeType'));
              if ($tempEmployeeContractType == null) {
              $view->setStatusCode(404)->setHeader("error", "The employeeType ID " . $paramFetcher->get('employeeType') . " is invalid");
              return $view;
              }
              $contract->setEmployeeContractTypeEmployeeContractType($tempEmployeeContractType); */

            $tempPosition = $positionRepo->find($paramFetcher->get('position'));
            if ($tempPosition == null) {
                $view->setStatusCode(404)->setHeader("error", "The position ID " . $paramFetcher->get('position') . " is invalid");
                return $view;
            }
            $contract->setPositionPosition($tempPosition);

            $tempTimeCommitment = $timeCommitmentRepo->find($paramFetcher->get('timeCommitment'));
            if ($tempTimeCommitment == null) {
                $view->setStatusCode(404)->setHeader("error", "The timeCommitment ID " . $paramFetcher->get('timeCommitment') . " is invalid");
                return $view;
            }
            $contract->setTimeCommitmentTimeCommitment($tempTimeCommitment);

            $startDate = $paramFetcher->get('startDate');
            $endDate = null;
            $datetime = new DateTime($startDate);
            $contract->setStartDate($datetime);
            $contract->setEndDate($endDate);

            /* $workTimeStart = $paramFetcher->get('workTimeStart');
              $datetime = new DateTime();
              $datetime->setTime($workTimeStart['hour'], $workTimeStart['minute']);
              $contract->setWorkTimeStart($datetime);

              $workTimeEnd = $paramFetcher->get('workTimeEnd');
              $datetime = new DateTime();
              $datetime->setTime($workTimeEnd['hour'], $workTimeEnd['minute']);
              $contract->setWorkTimeEnd($datetime); */

            if ($contract->getContractTypeContractType()->getName() == "Término fijo") {
                $endDate = $paramFetcher->get('endDate');
                $datetime = new DateTime($endDate);
                $contract->setEndDate($datetime);
            }
            if ($contract->getTimeCommitmentTimeCommitment()->getName() == "Trabajo por días") {
                $actualWeekWorkableDayss = $paramFetcher->get('weekWorkableDays');
                $actualWeekWorkableDays = $paramFetcher->get('weekDays');
                $sisben = $paramFetcher->get('sisben');
                $contract->setWorkableDaysMonth($actualWeekWorkableDayss * 4);
                $contract->setSisben($sisben);
                $contract->setSalary($paramFetcher->get('salaryD') * $actualWeekWorkableDayss * 4);

                $workableDays = $contract->getWeekWorkableDays();
                foreach ($workableDays as $workableDay) {

                    $flagActualRemove = true;
                    $ifFound = null;
                    /** @var WeekWorkableDays $workableDay */
                    foreach ($actualWeekWorkableDays as $key => $value) {
                        if ($workableDay->getDayName() == $value) {
                            $flagActualRemove = false;
                            $ifFound = $key;
                            break;
                        }
                    }
                    if ($flagActualRemove) {
                        $contract->removeWeekWorkableDay($workableDay);
                        $em->remove($workableDay);
                        continue;
                    } else {
                        unset($actualWeekWorkableDays[$ifFound]);
                    }
                }
                foreach ($actualWeekWorkableDays as $key => $value) {
                    $weekWorkableDay = new WeekWorkableDays();
                    $weekWorkableDay->setContractContract($contract);
                    $weekWorkableDay->setDayName($value);
                    $contract->addWeekWorkableDay($weekWorkableDay);
                }

                $contract->setWorkableDaysMonth($contract->getWeekWorkableDays()->count()*4);
            } else {
                $contract->setSalary($paramFetcher->get('salary'));
                $contract->setWorkableDaysMonth(30);
                $contract->setTransportAid($paramFetcher->get('transportAid'));
            }

            //Workplaces and Benefits
            $benefits = $paramFetcher->get("idsBenefits");
            $benefitsType = $paramFetcher->get("benefType");
            $benefitsAmount = $paramFetcher->get("amountBenefits");
            $benefitsPeriod = $paramFetcher->get("periodicityBenefits");
            $workplace = $paramFetcher->get("idWorkplace");
            /** @var Workplace $realWorkplace */
            $realWorkplace = $workplaceRepo->find($workplace);

            if ($realWorkplace == null) {
                $view->setStatusCode(404)->setHeader("error", "The workplace ID " . $workplace . " is invalid");
                return $view;
            }
            $contract->setWorkplaceWorkplace($realWorkplace);
            /* $contractHasBenefits = $contract->getBenefits();
              $idsCHB = array();
              /** @var ContractHasBenefits $contractHasBenefit
              foreach ($contractHasBenefits as $contractHasBenefit) {
              $idsCHB[$contractHasBenefit->getIdContractHasBenefits()] = $contractHasBenefit->getIdContractHasBenefits();
              }
              for ($i = 0; $i < count($benefitsType); $i++) {
              $realContractHasBenefit = null;
              $flagExist = false;
              if ($benefits[$i] != null) {
              $flagExist = true;
              /** @var ContractHasBenefits $realContractHasBenefit
              $realContractHasBenefit = $contracHasBenefitRepo->find($benefits[$i]);
              unset($idsCHB[$realContractHasBenefit->getIdContractHasBenefits()]);
              }
              /** @var Benefits $realBenefit
              $realBenefit = $benefitRepo->find($benefitsType[$i]);
              if ($realBenefit == null) {
              $view->setStatusCode(404)->setHeader("error", "The Benefit ID " . $benefitsType[$i] . " is invalid");
              return $view;
              }
              if (!$flagExist) {
              $realContractHasBenefit = new ContractHasBenefits();
              }
              $realContractHasBenefit->setAmount($benefitsAmount[$i]);
              $realContractHasBenefit->setBenefitsBenefits($realBenefit);
              $realContractHasBenefit->setPeriodicity($benefitsPeriod[$i]);
              if (!$flagExist) {
              $contract->addBenefit($realContractHasBenefit);
              }
              }
              foreach ($idsCHB as $key => $value) {
              $toRemove = $contracHasBenefitRepo->find($value);
              $em->remove($toRemove);
              }
              $contract->setBenefitsConditions($paramFetcher->get('benefitsConditions')); */
            //turn on current contract
            $contract->setState(1);

            $errors = $this->get('validator')->validate($contract, array('Update'));
            $view = View::create();
            if (count($errors) == 0) {
                if ($employee->getRegisterState() == 50) {
                    $employee->setRegisterState(75);
                }
                $em->persist($employee);
                $em->flush();
                $view->setData(array('response' => array('idContract' => $contract->getIdContract())))->setStatusCode(200);

                $this->addFlash(
                    'notice', 'Your changes were saved!'
                );
                return $view;
            } else {
                $this->addFlash(
                    'error', 'Your changes were NO saved!'
                );
                $view = $this->getErrorsView($errors);
                return $view;
            }
        }
    }

    /**
     * Save data<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Save data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when any Id does not exist in the DB"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     *
     * @RequestParam(name="idEmployer", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="severances", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="arl", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="economicalActivity", nullable=false, strict=true, description="employee type.")
     * @RequestParam(array=true, name="idEmployerHasEmployee", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="beneficiaries", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="pension", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="wealth", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="register_social_security", nullable=true, strict=true, description="afiliaciones")
     *
     * @return View
     */
    public function postMatrixChooseSubmitAction(ParamFetcher $paramFetcher)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $view = View::create();
            return $view->setStatusCode(401)->setData(array("error" => array("login" => "El usuario no está logeado"), "url" => $this->generateUrl("fos_user_security_login")));
        }
        $flag = false;
        $save = $this->saveMatrixChooseSubmitStep1($paramFetcher);
        if ($save->getData('response')['response']['message'] == 'added') {
            $save2 = $this->saveMatrixChooseSubmitStep2($paramFetcher);
            if ($save2->getData('response')['response']['message'] == 'added') {
                $save3 = $this->saveMatrixChooseSubmitStep3($paramFetcher);
                if ($save3->getData('response')['response']['message'] == 'added') {
                    /** @var User $user */
                    $user = $this->getUser();
                    $user->setStatus(2);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $flag = true;
                } else {
                    return $save3;
                }
            } else {
                return $save2;
            }
        } else {
            return $save;
        }

        if ($flag) {
            //return $this->forward('RocketSellerTwoPickBundle:Default:subscriptionChoices');
            return $this->redirectToRoute('ajax');
            //$view->setData(array('url' => $this->generateUrl('subscription_choices')))->setStatusCode(200);
        } else {
            $view = View::create();
            $view->setData(array('response' => array('message' => 'something went wrong')))->setStatusCode(400);
            return $view;
        }
    }

    private function saveMatrixChooseSubmitStep3(ParamFetcher $paramFetcher)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            $view = View::create();
            $view->setData(array('error' => array('employee' => 'user not logged')))->setStatusCode(403);
            return $view;
        }

        $register_social_security = $paramFetcher->get("register_social_security");

        $employerHasEmployeeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:EmployerHasEmployee');
        $employerHasEmployees = $register_social_security['employerHasEmployees'];
        $idEmployer = $register_social_security['idEmployer'];

        foreach ($employerHasEmployees as $employerHasEmployee) {
            /** @var EmployerHasEmployee $realEmployerHasEmployee */
            $realEmployerHasEmployee = $employerHasEmployeeRepo->find($employerHasEmployee['idEmployerHasEmployee']);
            $realEmployeer = $realEmployerHasEmployee->getEmployerEmployer();
            if ($idEmployer == $realEmployeer->getIdEmployer()) {
                $this->validateDocumentsEmployee($realEmployerHasEmployee->getEmployeeEmployee());
                $this->validateEntitiesEmployee($realEmployerHasEmployee->getEmployeeEmployee());
            } else {
                $view = View::create();
                $view->setData(array('error' => array('employee' => 'do not contain')))->setStatusCode(401);
                return $view;
            }
        }

        $this->validateDocumentsEmployer($idEmployer);

        $view = View::create();
        $view->setData(array('response' => array('message' => 'added')))->setStatusCode(200);
        return $view;
    }

    private function validateEntitiesEmployee($realEmployee)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $personEmployee = $realEmployee->getPersonPerson();
        $employeeHasEntityRepo = $em->getRepository('RocketSellerTwoPickBundle:EmployeeHasEntity');
        $entities_b = $employeeHasEntityRepo->findByEmployeeEmployee($realEmployee);
        if (gettype($entities_b) != "array") {
            $entities[] = $entities;
        } else {
            $entities = $entities_b;
        }
        //foreach ($entities as $key => $value) {
        $msj = "Subir documentos de " . $personEmployee->getFullName() . " para afiliarlo a las entidades.";
        $url = $this->generateUrl("show_documents", array('id' => $personEmployee->getIdPerson()));
        $this->createNotification($user->getPersonPerson(), $msj, $url);
        //}
    }

    private function validateDocumentsEmployee($realEmployee)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $person = $realEmployee->getPersonPerson();
        $documentsRepo = $em->getRepository('RocketSellerTwoPickBundle:Document');
        $documents = $documentsRepo->findByPersonPerson($person);

        $docs = array('Cedula' => false, 'Contrato' => false);
        foreach ($docs as $type => $status) {
            foreach ($documents as $key => $document) {
                if ($type == $document->getDocumentTypeDocumentType()->getName()) {
                    $docs[$type] = true;
                    break;
                }
            }
            if (!$docs[$type]) {
                $msj = "";
                if ($type == 'Cedula') {
                    $msj = "Subir copia del documento de identidad de " . $person->getFullName();
                    $documentType = 'Cedula';
                } elseif ($type == 'Contrato') {
                    $msj = "Subir copia del contrato de " . $person->getFullName();
                    $documentType = 'Contrato';
                }
                $documentType = $em->getRepository('RocketSellerTwoPickBundle:DocumentType')->findByName($documentType)[0];
                $url = $this->generateUrl("documentos_employee", array('id' => $person->getIdPerson(), 'idDocumentType' => $documentType->getIdDocumentType()));
                //$url = $this->generateUrl("api_public_post_doc_from");
                $this->createNotification($user->getPersonPerson(), $msj, $url);
            }
        }
    }

    private function validateDocumentsEmployer($idEmployer)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $employerRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employer');
        $realEmployer = $employerRepo->find($idEmployer);
        $person = $realEmployer->getPersonPerson();

        $documentsRepo = $em->getRepository('RocketSellerTwoPickBundle:Document');
        $documents = $documentsRepo->findByPersonPerson($person);

        $docs = array('Cedula' => false, 'RUT' => false, 'Carta autorización Symplifica' => false);
        foreach ($docs as $type => $status) {
            foreach ($documents as $key => $document) {
                if ($type == $document->getDocumentTypeDocumentType()->getName()) {
                    $docs[$type] = true;
                    break;
                }
            }
            if (!$docs[$type]) {
                $msj = "";
                if ($type == 'Cedula') {
                    $msj = "Subir copia del documento de identidad de " . $person->getFullName();
                    $documentType = 'Cedula';
                } elseif ($type == 'RUT') {
                    $msj = "Subir copia del RUT de " . $person->getFullName();
                    $documentType = 'Contrato';
                } elseif ($type == 'Carta autorización Symplifica') {
                    $msj = "Subir carta de autorización symplifica de " . $person->getFullName();
                    $documentType = 'Carta autorización Symplifica';
                }
                $documentType = $em->getRepository('RocketSellerTwoPickBundle:DocumentType')->findByName($documentType)[0];
                $url = $this->generateUrl("documentos_employee", array('id' => $person->getIdPerson(), 'idDocumentType' => $documentType->getIdDocumentType()));
                //$url = $this->generateUrl("api_public_post_doc_from");
                $this->createNotification($user->getPersonPerson(), $msj, $url);
            }
        }
    }

    private function createNotification($person, $descripcion, $url)
    {
        $notification = new Notification();
        $notification->setPersonPerson($person);
        $notification->setStatus(1);
        $notification->setType('alert');
        $notification->setDescription($descripcion);
        $notification->setRelatedLink($url);
        $notification->setAccion('Subir documento');
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the requested Ids don't exist"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="idEmployer", nullable=true, strict=true, description="employee type.")
     * @RequestParam(array=true, name="idEmployerHasEmployee", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="beneficiaries", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="pension", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="wealth", nullable=true, strict=true, description="benefits of the employee.")
     * @RequestParam(array=true, name="register_social_security", nullable=true, strict=true, description="afiliaciones")
     * @return View
     */
    public function postMatrixChooseSubmitStep1Action(ParamFetcher $paramFetcher)
    {
        return $this->saveMatrixChooseSubmitStep1($paramFetcher);
    }

    private function saveMatrixChooseSubmitStep1(ParamFetcher $paramFetcher)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user == null) {
            $view = View::create();
            $view->setData(array('error' => array('employee' => 'user not logged')))->setStatusCode(403);
            return $view;
        }

        $employerRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employer');
        $employerHasEmployeeRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:EmployerHasEmployee');
        $entityRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Entity');

        $register_social_security = $paramFetcher->get("register_social_security");
        /** @var Employer $realEmployer */
        $realEmployer = $employerRepo->find($register_social_security['idEmployer']);
        if ($user->getPersonPerson()->getEmployer() != $realEmployer) {
            $view = View::create();
            $view->setData(array('error' => array('user' => 'not the logged user')))->setStatusCode(403);
            return $view;
        }

        $flag = false;
        $employerHasEmployees = $register_social_security['employerHasEmployees'];
        $realEmployerHasEmployees = $realEmployer->getEmployerHasEmployees();

        foreach ($employerHasEmployees as $employerHasEmployee) {

            /** @var EmployerHasEmployee $realEmployerHasEmployee */
            $realEmployerHasEmployee = $employerHasEmployeeRepo->find($employerHasEmployee['idEmployerHasEmployee']);
            if ($realEmployerHasEmployees->contains($realEmployerHasEmployee)) {

                /** @var Entity $tempPens */
                $tempPens = $entityRepo->find($employerHasEmployee['pension']);

                /** @var Entity $tempWealth */
                $tempWealth = $entityRepo->find($employerHasEmployee['wealth']);

                $beneficiarie = $employerHasEmployee['beneficiaries'];

                if ($tempPens == null || $tempWealth == null) {
                    $view = View::create();
                    $view->setData(array('error' => array('entity' => 'do not exist')))->setStatusCode(404);
                    return $view;
                }
                $realEmployee = $realEmployerHasEmployee->getEmployeeEmployee();
                $realEmployeeEnt = $realEmployee->getEntities();
                $em = $this->getDoctrine()->getManager();

                if ($realEmployeeEnt->count() == 0) {

                    $employeeHasEntityPens = new EmployeeHasEntity();
                    $employeeHasEntityPens->setEmployeeEmployee($realEmployee);
                    $employeeHasEntityPens->setEntityEntity($tempPens);
                    $realEmployee->addEntity($employeeHasEntityPens);
                    $em->persist($employeeHasEntityPens);

                    $employeeHasEntityWealth = new EmployeeHasEntity();
                    $employeeHasEntityWealth->setEmployeeEmployee($realEmployee);
                    $employeeHasEntityWealth->setEntityEntity($tempWealth);
                    $realEmployee->addEntity($employeeHasEntityWealth);
                    $em->persist($employeeHasEntityWealth);

                    $realEmployee->setAskBeneficiary($beneficiarie);
                    $em->persist($realEmployee);

                    $em->flush();
                } else {
                    /** @var EmployeeHasEntity $rEE */
                    foreach ($realEmployeeEnt as $rEE) {
                        if ($rEE->getEntityEntity()->getEntityTypeEntityType() == "EPS") {
                            $rEE->setEntityEntity($tempWealth);
                            $em->persist($rEE);
                        }
                        if ($rEE->getEntityEntity()->getEntityTypeEntityType() == "Pension") {
                            $rEE->setEntityEntity($tempPens);
                            $em->persist($rEE);
                        }
                    }
                    $realEmployee->setAskBeneficiary($beneficiarie);
                    $em->persist($realEmployee);
                    $em->flush();
                }
                $flag = true;
            } else {
                $view = View::create();
                $view->setData(array('error' => array('employee' => 'do not contain')))->setStatusCode(401);
                return $view;
            }
        }

        if ($flag) {
            $view = View::create();
            $view->setData(array('response' => array('message' => 'added')))->setStatusCode(200);
            return $view;
        } else {
            $view = View::create();
            $view->setData(array('response' => array('message' => 'something went wrong')))->setStatusCode(400);
            return $view;
        }
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the requested Ids don't exist"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="idEmployer", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="severances", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="arl", nullable=false, strict=true, description="employee type.")
     * @RequestParam(name="economicalActivity", nullable=true, strict=true, description="employee type.")
     * @RequestParam(array=true, name="register_social_security", nullable=true, strict=true, description="afiliaciones")
     * @return View
     */
    public function postMatrixChooseSubmitStep2Action(ParamFetcher $paramFetcher)
    {
        return $this->saveMatrixChooseSubmitStep2($paramFetcher);
    }

    private function saveMatrixChooseSubmitStep2(ParamFetcher $paramFetcher)
    {
        /** @var User $user */
        $user = $this->getUser();
        $view = View::create();

        if ($user == null) {
            return $view->setData(array('error' => array('User' => 'user not logged')))->setStatusCode(403);
        }

        $register_social_security = $paramFetcher->get("register_social_security");

        $idEmployer = $register_social_security['idEmployer'];
        $employerRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employer');
        $entityRepo = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Entity');
        /** @var Employer $realEmployer */
        $realEmployer = $employerRepo->find($idEmployer);
        if ($user->getPersonPerson()->getEmployer() != $realEmployer) {
            return $view->setData(array('error' => array('Employer' => 'not the loged employer')))->setStatusCode(403);
        }
        $realEmployer->setEconomicalActivity($register_social_security['economicalActivity']);
        /** @var Entity $realArl */
        $realArl = $entityRepo->find($register_social_security['arl']);
        /** @var Entity $realSeverances */
        $realSeverances = $entityRepo->find($register_social_security['severances']);
        if ($realSeverances == null || $realArl == null) {
            return $view->setData(array('error' => array('Entity' => 'Entities Not found')))->setStatusCode(404);
        }
        $realEmployerEnt = $realEmployer->getEntities();
        $em = $this->getDoctrine()->getManager();

        if ($realEmployerEnt->count() == 0) {
            $realArlHasEmployer = new EmployerHasEntity();
            $realArlHasEmployer->setEntityEntity($realArl);
            $realArlHasEmployer->setEmployerEmployer($realEmployer);
            $realEmployer->addEntity($realArlHasEmployer);
            $realSevereancesHasEmployer = new EmployerHasEntity();
            $realSevereancesHasEmployer->setEntityEntity($realSeverances);
            $realSevereancesHasEmployer->setEmployerEmployer($realEmployer);
            $realEmployer->addEntity($realSevereancesHasEmployer);
            $em->persist($realEmployer);
            $em->flush();
        } else {
            /** @var EmployerHasEntity $rEE */
            foreach ($realEmployerEnt as $rEE) {
                if ($rEE->getEntityEntity()->getEntityTypeEntityType() == "ARL") {
                    $rEE->setEntityEntity($realArl);
                }
                if ($rEE->getEntityEntity()->getEntityTypeEntityType() == "CC Familiar") {
                    $rEE->setEntityEntity($realSeverances);
                }
            }
            $em->persist($realEmployer);
            $em->flush();
        }

        return $view->setData(array('response' => array('message' => 'added')))->setStatusCode(200);
    }

    /**
     * Create a Person from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new person from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the requested Ids don't exist"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="idContract", nullable=false, strict=true, description="the id of the contract")
     * @return View
     */
    public function getVacationsDaysTaken(ParamFetcher $paramFetcher)
    {
        $contractRepo = $this->getDoctrine()->getRepository("RocketSellerTwoPickBundle:Contract");
        /** @var Contract $contract */
        $contract = $contractRepo->find($paramFetcher->get("idContract"));
        $payRolls = $contract->getPayrolls();
        /** @var Payroll $payRoll */
        foreach ($payRolls as $payRoll) {
            $novelties = $payRoll->getNovelties();
            /** @var Novelty $novelty */
            foreach ($novelties as $novelty) {
                if ($novelty->getNoveltyTypeNoveltyType()->getGrupo() == "Vacaciones") {
                    //TODO Necesitamos los festivos y eso es super gg
                }
            }
        }
    }

    /**
     * Get the validation errors
     *
     * @param ConstraintViolationList $errors Validator error list
     *
     * @return View
     */
    protected function getErrorsView(ConstraintViolationList $errors)
    {
        $msgs = array();
        $errorIterator = $errors->getIterator();
        foreach ($errorIterator as $validationError) {
            $msg = $validationError->getMessage();
            $params = $validationError->getMessageParameters();
            $msgs[$validationError->getPropertyPath()][] = $this->get('translator')->trans($msg, $params, 'validators');
        }
        $view = View::create($msgs);
        $view->setStatusCode(400);

        return $view;
    }

}
