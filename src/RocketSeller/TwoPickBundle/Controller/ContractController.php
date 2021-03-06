<?php

namespace RocketSeller\TwoPickBundle\Controller;

use RocketSeller\TwoPickBundle\Entity\ContractHasBenefits;
use RocketSeller\TwoPickBundle\Entity\ContractHasWorkplace;
use RocketSeller\TwoPickBundle\Entity\Contract;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RocketSeller\TwoPickBundle\Form\ContractRegistration;
use RocketSeller\TwoPickBundle\Form\expressContractRegistration;
use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use FOS\RestBundle\Routing\Loader\Reader\RestActionReader;
use RocketSeller\TwoPickBundle\Entity\Workplace;
use RocketSeller\TwoPickBundle\Entity\Payroll;
use RocketSeller\TwoPickBundle\Entity\Employee;
use RocketSeller\TwoPickBundle\Entity\Employer;
use RocketSeller\TwoPickBundle\Traits\EmployerHasEmployeeMethodsTrait;
use RocketSeller\TwoPickBundle\Traits\ContractMethodsTrait;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContractController extends Controller
{

    use EmployerHasEmployeeMethodsTrait;

use ContractMethodsTrait;

    /**
     * @param Id el id de la relación entre empleado y empleador EmployerHasEmployee
     * @return
     * */
    public function addContractAction(Request $request, $id)
    {
        if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userWorkplaces = $user->getPersonPerson()->getEmployer()->getWorkplaces();
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:EmployerHasEmployee');
        $employerHasEmployee = $repository->find($id);
        $contracts = $employerHasEmployee->getContracts();
        $contract = new Contract();

        $contract->addWorkplace(new ContractHasWorkplace($contract, null));
        $contract->addBenefit(new ContractHasBenefits($contract, null));


        //modificar los workplaces
        $form = $this->createForm(new ContractRegistration($userWorkplaces), $contract);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $contract->setEmployerHasEmployeeEmployerHasEmployee($employerHasEmployee);
            $em = $this->getDoctrine()->getManager();            
            $em->persist($contract);
            $em->flush();

            return $this->redirectToRoute('show_dashboard');
        }
        return $this->render('RocketSellerTwoPickBundle:Contract:contractForm.html.twig', array('form' => $form->createView())
        );
    }

    /**
     * @param Id el id de la relación entre empleado y empleador EmployerHasEmployee
     * @return
     * */
    public function showContractsAction(Request $request, $id)
    {

        $contracts = $this->showContracts($id);

        return $this->render(
                        'RocketSellerTwoPickBundle:Contract:contractManager.html.twig', array(
                    'contracts' => $contracts,
                    'idEmployerEmployee' => $id)
        );
    }

    /**
     * @param Request $request
     * @param integer $id El Id del contrato a ver
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewContractAction(Request $request, $id)
    {
        $contract = $this->contractDetail($id);

        return $this->render('RocketSellerTwoPickBundle:Contract:view-contract.html.twig', array(
                    "contract" => $contract
                        )
        );
    }

    /**
     * @param Id el id de la relación entre empleado y empleador EmployerHasEmployee
     * @return
     * */
    public function editContractAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Contract');
        $userWorkplaces = $user->getPersonPerson()->getEmployer()->getWorkplaces();
        $contract = $repository->find($id);

        $form = $this->createForm(new ContractRegistration($userWorkplaces), $contract);
        $current = new ArrayCollection();
        $current['benefits'] = new ArrayCollection();
        $current['workplaces'] = new ArrayCollection();
        foreach ($contract->getBenefits() as $value) {
            $current['benefits']->add($value);
        }
        foreach ($contract->getWorkplaces() as $key => $value) {
            $current['workplaces']->add($value);
        }
        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($current['benefits'] as $benefit) {
                if (false === $contract->getBenefits()->contains($benefit)) {
                    $em->remove($benefit);
                }
            }
            foreach ($current['workplaces'] as $workplace) {
                if (false === $contract->getWorkplaces()->contains($workplace)) {
                    $em->remove($workplace);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->redirectToRoute('show_dashboard');
        }
        return $this->render('RocketSellerTwoPickBundle:Contract:contractForm.html.twig', array('form' => $form->createView()));
    }

    /**
     * Cambia el estado del contrato para activarlo o desactivarlo
     * @param string $id id del contrato
     * @return type
     */
    public function stateContractAction($id)
    {
        $repoContract = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Contract');
        $contract = $repoContract->find($id);
        $state = '';
        if ($contract->getState() == 1) {
            $state = 'Inactive';
            $contract->setState(0);
        } else if ($contract->getState() == 0) {
            $state = 'Active';
            $contract->setState(1);
        } else {
            return false;
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($contract);
        $em->flush();
        return new JsonResponse(array('state' => $state));
    }
    public function addContractExpressAction(Request $request, $id,$idEmployee)
    {        
        //$employee = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee')->find($idEmployee);
        $employee = $this->loadClassById($idEmployee,"Employee");
        
        $person = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Person')->find($id);
        $employer = $this->loadClassByArray(array("personPerson"=>$person),"Employer");
        
        $employerHasEmployee = $this->loadClassByArray(array(
            "employerEmployer"=>$employer,
            "employeeEmployee"=>$employee
            ),"EmployerHasEmployee");

        /*$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userWorkplaces = $user->getPersonPerson()->getEmployer()->getWorkplaces();
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:EmployerHasEmployee');
        $employerHasEmployee = $repository->find($id);
        */
        $employerWorkplaces = $employer->getWorkplaces();
        $contracts = $employerHasEmployee->getContracts();
        foreach ($contracts as $cont) {
            if ($cont->getState()==1) {
                $contract = $cont;
            }
        }
        if (!$contract) {
            $contract = new Contract();
        }        

        //$contract->addWorkplace(new ContractHasWorkplace($contract, null));
        //$contract->addBenefit(new ContractHasBenefits($contract, null));


        //modificar los workplaces
        $form = $this->createForm(new expressContractRegistration($employerWorkplaces), $contract);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $contract->setEmployerHasEmployeeEmployerHasEmployee($employerHasEmployee);            
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();
            $employee = $employerHasEmployee->getEmployeeEmployee();
            $employee->setRegisterState(60);
            $em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('back_employer_menu',array('id'=>$employerHasEmployee->getEmployerEmployer()->getPersonPerson()->getIdPerson()));
        }
        return $this->render('RocketSellerTwoPickBundle:BackOffice:contractRegister.html.twig', array('form' => $form->createView())
        );
    }
    public function loadClassByArray($array, $entity)
    {
        $loadedClass = $this->getdoctrine()
        ->getRepository('RocketSellerTwoPickBundle:'.$entity)
        ->findOneBy($array);
        return $loadedClass;
    }
    public function loadClassById($parameter, $entity)
    {
        $loadedClass = $this->getdoctrine()
        ->getRepository('RocketSellerTwoPickBundle:'.$entity)
        ->find($parameter);
        return $loadedClass;
    }   

}
