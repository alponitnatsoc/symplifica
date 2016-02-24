<?php

namespace RocketSeller\TwoPickBundle\Controller;

use RocketSeller\TwoPickBundle\Entity\Employer;
use RocketSeller\TwoPickBundle\Entity\EmployerHasEmployee;
use RocketSeller\TwoPickBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashBoardController extends Controller
{

    /**
     * Maneja el registro de una nueva persona con los datos básicos, 
     * TODO agregar todos los campos de los wireframes
     * @param el Request que manjea el form que se imprime
     * @return La vista de el formulario de la nueva persona
     * */
    public function showDashBoardAction(Request $request)
    {
        //¿Cómo vamos a hacer para saber en que parte del form está el usuario?
        //para el render se envía un array steps en el cuals e le puede agregar el estado el usuario
        /* @var $user User */
        $user = $this->getUser();
        $paymentState = $user->getPaymentState();
        $stateRegister = 0;
        $stateEmployees = 0;
        $stateAfiliation = 0;
        $idCurrentEmployee = -1;
        /** @var Employer $employer */
        $employer = $user->getPersonPerson()->getEmployer();
        if ($employer == null) {
            $stateRegister+=10;
        } else {
            //el registro del empleador está completo
            $stateRegister = $employer->getRegisterState();
            //ahora vamos a ver el de los empleados
            $employees = $employer->getEmployerHasEmployees();
            //se calcula cuantos empleados tenemos 0 ó *
            $numEmployees = count($employees);
            //para agregar procentajes respectivos la minima unidad es el registro del 
            //empleado sin el contrato, cuando se le agrega el contrato es otra unidad 
            //minima de un 100%
            //si existen empleados se puede empezar a subir el 0%
            if ($numEmployees > 0) {
                $minUnit = 100 / ($numEmployees + 1);
                /** @var EmployerHasEmployee $value */
                foreach ($employees as $key => $value) {
                    //para cada empleado se mira si tiene por lo menos 1 contrato
                    $stateEmployees+=$value->getEmployeeEmployee()->getRegisterState();
                    if ($value->getEmployeeEmployee()->getEntities()->count() != 0) {
                        $stateAfiliation+=$minUnit;
                    }
                }
                if ($employer->getEntities()->count() > 0) {
                    $stateAfiliation+=$minUnit;
                }
                $stateEmployees = $stateEmployees / $numEmployees;
            }
        }


        $step1 = array(
            'url' => $this->generateUrl('edit_profile'),
            'name' => "Mis datos como empleador",
            'state' => $stateRegister,
            'paso' => 1,
            'boxStyle' => "big",
            'stateMessage' => $stateRegister != 100 ? "Iniciar" : "Editar",);
        $steps ['0'] = $step1;


        if ($stateEmployees == 100) {
            $step2 = array(
                'url' => $this->generateUrl('manage_employees'),
                'name' => "Datos de mis empleados",
                'state' => $stateEmployees,
                'paso' => 2,
                'boxStyle' => "big",
                'stateMessage' => $stateEmployees != 100 ? "Iniciar" : "Editar",);
            $steps ['1'] = $step2;

            $step3 = array(
                'url' => $this->generateUrl('register_employee', array('id' => $idCurrentEmployee)),
                'name' => "Agregar un nuevo Empleado?",
                'state' => 0,
                'paso' => 2,
                'boxStyle' => "small",
                'stateMessage' => "Iniciar",);
            $steps ['2'] = $step3;
        } else {
            $step2 = array(
                'url' => $stateRegister != 100 ? "" : $this->generateUrl('register_employee', array('id' => $idCurrentEmployee)),
                'name' => "Datos de los empleados",
                'state' => $stateEmployees,
                'paso' => 2,
                'boxStyle' => "big",
                'stateMessage' => $stateEmployees != 100 ? "Iniciar" : "Editar",);
            $steps ['1'] = $step2;
        }
        $step4 = array(
            'url' => $paymentState ? "" : $this->generateUrl('subscription_choices'),
            'name' => "Pago afiliación",
            'paso' => 3,
            'state' => $paymentState,
            'boxStyle' => "big",
            'stateMessage' => !$paymentState ? "Iniciar" : "Editar",);
        $steps ['3'] = $step4;

        $step5 = array(
            'url' => $stateEmployees != 100 ? "" : $this->generateUrl('matrix_choose'),
            'name' => "Finalizar afiliación",
            'paso' => 4,
            'state' => $stateAfiliation,
            'boxStyle' => "big",
            'stateMessage' => $stateAfiliation != 100 ? "Iniciar" : "Editar",);
        $steps ['4'] = $step5;

        return $this->render('RocketSellerTwoPickBundle:General:dashBoard.html.twig', array('steps' => $steps));
    }

}

?>