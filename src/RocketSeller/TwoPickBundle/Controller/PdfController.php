<?php

namespace RocketSeller\TwoPickBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use RocketSeller\TwoPickBundle\pdf\cafeSalud;
class PdfController extends Controller
{
    public function indexAction()
    {
    	$person = $this->getUser()->getPersonPerson();
    	$pd = new cafeSalud();
		$pd->AddPage();
		$pd->setFile("/home/nicolas/Desktop/cafe_salud_page1.pdf");
		$tplIdx = $pd->importPage(1);
		$pd->useTemplate($tplIdx, 10, 10,210);

		$pd->selectTypeField(3);
		$pd->setActualDate();
		$pd->writePersonInfo($person);

		return new Response($pd->Output(), 200, array(
        'Content-Type' => 'application/pdf'));
        
    }
}