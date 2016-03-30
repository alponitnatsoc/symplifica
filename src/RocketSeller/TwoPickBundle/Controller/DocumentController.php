<?php

namespace RocketSeller\TwoPickBundle\Controller;

use RocketSeller\TwoPickBundle\Entity\Document;
use RocketSeller\TwoPickBundle\Entity\Person;
use RocketSeller\TwoPickBundle\Entity\User;
use RocketSeller\TwoPickBundle\Form\DocumentRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sonata\Bundle\DemoBundle\Model\MediaPreview;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Application\Sonata\MediaBundle\Entity\GalleryHasMedia;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use RocketSeller\TwoPickBundle\Traits\ContractMethodsTrait;
use RocketSeller\TwoPickBundle\Traits\BasicPersonDataMethodsTrait;
use RocketSeller\TwoPickBundle\Traits\EmployerHasEmployeeMethodsTrait;
use RocketSeller\TwoPickBundle\Entity\Employee;
use RocketSeller\TwoPickBundle\Entity\Employer;
use RocketSeller\TwoPickBundle\Entity\Contract;
use RocketSeller\TwoPickBundle\Traits\EmployeeMethodsTrait;
use RocketSeller\TwoPickBundle\Traits\EmployerMethodsTrait;
use RocketSeller\TwoPickBundle\Entity\Novelty;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController extends Controller
{
    use ContractMethodsTrait;
    use BasicPersonDataMethodsTrait;
    use EmployerHasEmployeeMethodsTrait;
    use EmployeeMethodsTrait;
    use EmployerMethodsTrait;

	public function showDocumentsAction($id){
		$person = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Person')
		->find($id);
		$documents = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Document')
		->findByPersonPerson($person);
		return $this->render(
			'RocketSellerTwoPickBundle:Employee:documents.html.twig',
			array(
				'person' => $person,
				'documents' => $documents
				));
	}
	public function downloadContractAction($id)
	{
		switch ($id) {
			case 1:
				$filename = "terminoFijo.pdf";
				break;
			case 2:
				$filename = "terminoIndefinido.pdf";
				break;
		}
	    $path = $this->get('kernel')->getRootDir(). "/../web/public/";
	    $content = file_get_contents($path.$filename);

	    $response = new Response();

	    //set headers
	    $response->headers->set('Content-Type', 'mime/type');
	    $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

	    $response->setContent($content);
	    return $response;
	}
	public function downloadAuthAction()
	{

		$filename = "cartaAuth.pdf";
	    $path = $this->get('kernel')->getRootDir(). "/../web/public/";
	    $content = file_get_contents($path.$filename);

	    $response = new Response();

	    //set headers
	    $response->headers->set('Content-Type', 'mime/type');
	    $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

	    $response->setContent($content);
	    return $response;
	}
	public function addDocumentAction($id,Request $request){
		$em = $this->getDoctrine()->getManager();
		$person = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Person')
		->find($id);
		$document = new Document();
		$document->setPersonPerson($person);
		$document->setName('nombre');
		$form = $this->createForm(new DocumentRegistration(),$document);

		$form->handleRequest($request);

		if ($form->isValid()) {
			$medias=$document->getMediaMedia();
			/** @var Media $media */
			foreach ($medias as $media) {
								dump($media->getContentType());
				exit();
				$media->setBinaryContent($media);
				$media->setName('documento');
				$media->setProviderStatus(Media::STATUS_OK);
				$media->setProviderReference($media->getBinaryContent());
				$em->persist($media);
				$em->flush();
			}
			$em = $this->getDoctrine()->getManager();
			$em->persist($document);
			$em->flush();

			return $this->redirect('/pages?redirector=/matrix/choose');
		}
		return $this->render(
			'RocketSellerTwoPickBundle:Document:addDocumentForm.html.twig', array(
			    'form' => $form->createView(),
			    'id' => $id,
			    'idDocumentType' => 39,
			    'idNotification' => 0
			));
	}
	//JPG,PNG,TIF, BMP, DOC,PDF
	public function addDocAction($id,$idDocumentType,$idNotification,Request $request){
		$fileTypePermitted = array(
								'image/jpeg',
								'image/png',
								'application/pdf',
								'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
								);
		$em = $this->getDoctrine()->getManager();
		$person = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Person')
		->find($id);
		$documentType = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:DocumentType')
		->find($idDocumentType);
		$document=new Document();
		$document->setPersonPerson($person);
		$document->setStatus(1);
		$document->setName('Diferente');
		$document->setDocumentTypeDocumentType($documentType);

		$form = $this->createForm(new DocumentRegistration(),$document);

		$form->handleRequest($request);

		if ($form->isValid()) {

			if (in_array($document->getMediaMedia()->getContentType(),$fileTypePermitted))
			{

					$medias=$document->getMediaMedia();
					/** @var Media $media */
					foreach ($medias as $media) {

						$media->setBinaryContent($media);
						$media->setName($document->getName());
						$media->setProviderStatus(Media::STATUS_OK);
						$media->setProviderReference($media->getBinaryContent());
						$em->persist($media);
						$em->flush();


					}
					$em = $this->getDoctrine()->getManager();
					$em->persist($document);
					$em->flush();
					//$view = View::createView();

					//return new Response('guwegwei');
					if ($idNotification!=0) {
						$em = $this->getDoctrine()->getManager();
						$notification = $this->getDoctrine()
						->getRepository('RocketSellerTwoPickBundle:Notification')
						->find($idNotification);
						$notification->setStatus(0);
						$em->flush();
						return $this->redirect($request->server->get('HTTP_REFERER'));
					}else{
						return $this->redirectToRoute('matrix_choose', array('tab'=>3), 301);
					}

					}else{
						throw new \Exception('Formato no valido.');
					}

			//return $this->redirectToRoute('matrix_choose', array('tab'=>3), 301);
			//return $this->redirect('/pages?redirector=/matrix/choose');

		}
		return $this->render(
			'RocketSellerTwoPickBundle:Document:addDocumentForm.html.twig',
				array('form' => $form->createView(),'id'=>$id,'idDocumentType'=>$idDocumentType,'idNotification'=>$idNotification));
	}
	public function addDocModalAction($id,$idDocumentType,Request $request){
		$em = $this->getDoctrine()->getManager();
		$person = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Person')
		->find($id);
		$documentType = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:DocumentType')
		->find($idDocumentType);
		$document=new Document();
		$document->setPersonPerson($person);
		$document->setStatus(1);
		$document->setName('nombre');
		$document->setDocumentTypeDocumentType($documentType);

		$form = $this->createForm(new DocumentRegistration(),$document);

		$form->handleRequest($request);
		return $this->render(
			'RocketSellerTwoPickBundle:Document:addDocumentForm.html.twig',
				array('form' => $form->createView()));
	}
	public function editDocumentAction($id,$idDocument,Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$OldDocument = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Document')
		->find($idDocument);
		$OldDocument->setStatus(0);
		$em = $this->getDoctrine()->getManager();

		$person = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Person')
		->find($id);
		$documentType = $OldDocument->getDocumentTypeDocumentType();
		$document=new Document();
		$document->setPersonPerson($person);
		$document->setStatus(1);
		$document->setDocumentTypeDocumentType($documentType);
		$form = $this->createForm(new DocumentRegistration(),$document);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$medias=$document->getMediaMedia();
			/** @var Media $media */
			foreach ($medias as $media) {
				$media->setBinaryContent($media);
				$media->setName($document->getName());
				$media->setProviderStatus(Media::STATUS_OK);
				$media->setProviderReference($media->getBinaryContent());
				$em->persist($media);
				$em->flush();
			}
			$em = $this->getDoctrine()->getManager();
			$em->persist($document);
			$em->flush();
			$em->persist($OldDocument);
			$em->flush();
			return $this->redirectToRoute('employees_documents');
		}
		return $this->render(
			'RocketSellerTwoPickBundle:Document:addDocumentForm.html.twig',
				array('form' => $form->createView()));
		return $this->render('RocketSellerTwoPickBundle:Default:index.html.twig');
	}
	public function downloadDocAction($idDocument)
	{
		$em = $this->getDoctrine()->getManager();
		$document = $this->getDoctrine()
		->getRepository('RocketSellerTwoPickBundle:Document')
		->find($idDocument);
		return $this->redirect('/media/download/'.$document->getMediaMedia()->getId());
	}

	public function downloadDocumentPDFAction($document)
	{

        switch ($document):
    	    case "renuncia":
                $filename = "carta-renuncia.pdf";
                break;
    	    case "aceptacion":
    	        $filename = "carta-aceptacion.pdf";
    	        break;
    	    default:
    	        $filename = "cartaAuth.pdf";
    	        break;
        endswitch;

	    $path = $this->get('kernel')->getRootDir() . "/../web/public/docs/";
	    $content = file_get_contents($path . $filename);

	    $response = new Response();

	    //set headers
	    $response->headers->set('Content-Type', 'mime/type');
	    $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

	    $response->setContent($content);
	    return $response;
	}

	public function downloadDocumentsAction($ref, $id, $type, $attach)
	{
        switch ($ref){
    	    case "contrato":
    	        $ref = "contracts/" . $ref;
    	        // id del contrato
    	        /** @var Contract $contract */
    	        $contract = $this->contractDetail($id);
    	        $employerHasEmployee = $contract->getEmployerHasEmployeeEmployerHasEmployee();
    	        $employee = $employerHasEmployee->getEmployeeEmployee();
    	        $employeePerson = $employee->getPersonPerson();
    	        $employer = $employerHasEmployee->getEmployerEmployer();
    	        $employerPerson = $employer->getPersonPerson();

    	        $employeeInfo = array(
    	            'name' => $this->fullName($employeePerson->getIdPerson()),
    	            'docType' => $employeePerson->getDocumentType(),
    	            'docNumber' => $employeePerson->getDocument(),
    	            'residencia' => $employeePerson->getNeighborhood(),
    	            'tel' => $employeePerson->getPhones()->getValues()[0]
    	        );

    	        $employerInfo = array(
    	            'name' => $this->fullName($employerPerson->getIdPerson()),
    	            'docType' => $employerPerson->getDocumentType(),
    	            'docNumber' => $employerPerson->getDocument(),
                    'tel' => $employerPerson->getPhones()->getValues()[0],
    	            'email' => $employerPerson->getEmail()
    	        );

    	        $position = $contract->getPositionPosition()->getName();
//     	        $positionCode = $contract->getPositionPosition()->getPayrollCoverageCode();
    	        $identBy = $contract->getPositionPosition()->getIdentBy();

    	        $interno = $contract->getTransportAid();

    	        if ($interno) {
    	            if ($identBy == "m") { // Si el cargo es mayordomo
    	                $ref .= "-m";
    	            }
    	            $ref .= "-interno";
    	        }

    	        $timeCommitmentCode = $contract->getTimeCommitmentTimeCommitment()->getCode();
    	        if ($timeCommitmentCode == "XD") { // Si el contrato es por dias
    	            $ref .= "-xdias";
    	        }

    	        $contractType = $contract->getContractTypeContractType()->getName();

    	        $startDate = $contract->getStartDate();
    	        $endDate = $contract->getEndDate();

                //contrato termino fijo contractType payroll_code 2, indefinido payroll_code 1
    	        $payrollCode = $contract->getContractTypeContractType()->getPayrollCode();
    	        switch ($payrollCode) {
    	            default:
    	            case 1:
    	                $years = $months = $days = $years_months = null;
    	                $ref .= '-indefinido';
    	                break;
    	            case 2:
    	                $diff = $endDate->diff($startDate);
    	                $years = $diff->format("%y");
    	                $months = $diff->format("%m");
    	                $days = $diff->format("%d");
    	                $years_months = ($diff->format("%y") * 12) + $diff->format("%m");
    	                $ref .= '-fijo';
    	                break;
    	        }

    	        $contractInfo = array(
    	            "endDate" => $endDate,
    	            "startDate" => $startDate,
    	            "position" => $position,
    	            "salary" => $contract->getSalary(),
    	            "frequency" => $contract->getFrequencyFrequency()->getName(),
    	            "timeCommitment" => $contract->getTimeCommitmentTimeCommitment()->getName(),
    	            "interno" => $interno,
    	            "contractType" => $contractType,
    	            "workplace" => $contract->getWorkplaceWorkplace()->getCity()->getName(),
    	            "numero" => $contract->getIdContract(),
	                "years" => $years,
	                "months" => $months,
	                "years_months" => $years_months,
    	            "days" => $days,
    	            "workDays" => $contract->getWeekWorkableDays()->getValues(),
    	            "nWorkDays" => $contract->getWorkableDaysMonth(),
    	            "obligations" => $contract->getPositionPosition()->getObligations(),
    	            "identBy" => $identBy
    	        );

    	        $data = array(
    	            'employee' => $employeeInfo,
    	            'employer' => $employerInfo,
                    'contract' => $contractInfo
    	        );
    	        break;
	        case "otrosi":
	        case "cert-laboral-activo":
	        case "cert-laboral-retiro":
	        case "retiro-cesantias":
	        case "suspencion":
	        case "llamado-atencion":
	        case "aut-afiliacion-ss":
	        case "trato-datos":
    	        //$id de la relacion employerhasempployee
    	        /** @var Employee $employee */
    	        $employee = $this->getEmployee($id);
    	        /** @var Employer $employer */
    	        $employer = $this->getEmployer($id);
    	        /** @var Contract $contract */
    	        $contract = $this->getActiveContract($id);

    	        $employeePerson = $employee->getPersonPerson();
    	        $employeeInfo = array(
    	            'name' => $this->fullName($employeePerson->getIdPerson()),
    	            'docType' => $employeePerson->getDocumentType(),
    	            'docNumber' => $employeePerson->getDocument(),
    	            'docExpPlace' => $employeePerson->getDocumentExpeditionPlace(),
    	            'eps' => $this->getEmployeeEps($employee->getIdEmployee()),
    	            'afp' => $this->getEmployeeAfp($employee->getIdEmployee())
    	        );

    	        $employerPerson = $employer->getPersonPerson();
    	        $employerInfo = array(
    	            'name' => $this->fullName($employerPerson->getIdPerson()),
    	            'docType' => $employerPerson->getDocumentType(),
    	            'docNumber' => $employerPerson->getDocument(),
    	            'docExpPlace' => $employerPerson->getDocumentExpeditionPlace(),
    	            'arl' => $this->getEmployerArl($employer->getIdEmployer()),
    	            'ccf' => $this->getEmployerCcf($employer->getIdEmployer()),
    	            'email' => $employerPerson->getEmail(),
    	            'tel' => $employerPerson->getPhones()[0]
    	        );
    	        $contractInfo = array(
    	            'city' => $contract[0]->getWorkplaceWorkplace()->getCity()->getName(),
    	            'position' => $contract[0]->getPositionPosition()->getName(),
    	            'fechaInicio' => $contract[0]->getStartDate(),
    	            'fechaFin' => $contract[0]->getEndDate(),
    	            'numero' => $contract[0]->getIdContract(),
    	            'type' => $contract[0]->getContractTypeContractType()->getName(),
    	            'salary' => $contract[0]->getSalary()
    	        );

    	        $data = array(
    	            'employee' => $employeeInfo,
    	            'employer' => $employerInfo,
    	            'contract' => $contractInfo
    	        );
    	        break;
	        case "not-despido":
	        case "permiso":
	        case "dotacion":
	        case "aut-descuento":
	        case "descargo":
            case "vacaciones":
	            //$id de la novedad
	            $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Novelty');
	            /** @var Novelty $novelty */
	            $novelty = $repository->find($id);
	            $contract = $novelty->getPayrollPayroll()->getContractContract();
	            $empleHasEmplr = $contract->getEmployerHasEmployeeEmployerHasEmployee();
	            $employee = $empleHasEmplr->getEmployeeEmployee();
	            $employeePerson = $employee->getPersonPerson();
	            $employer = $empleHasEmplr->getEmployerEmployer();
	            $employerPerson = $employer->getPersonPerson();

	            $employeeInfo = array(
	                'name' => $this->fullName($employeePerson->getIdPerson()),
	                'docType' => $employeePerson->getDocumentType(),
	                'docNumber' => $employeePerson->getDocument(),
	                'docExpPlace' => $employeePerson->getDocumentExpeditionPlace()
	            );
	            $employerInfo = array(
	                'name' => $this->fullName($employerPerson->getIdPerson()),
	                'docType' => $employerPerson->getDocumentType(),
	                'docNumber' => $employerPerson->getDocument(),
	                'docExpPlace' => $employerPerson->getDocumentExpeditionPlace()
	            );
	            $contractInfo = array(
	                'city' => $contract->getWorkplaceWorkplace()->getCity()->getName(),
	                'position' => $contract->getPositionPosition()->getName(),
	                'fechaInicio' => $contract->getStartDate(),
	                'fechaFin' => $contract->getEndDate()
	            );
	            $noveltyInfo = array(
	                'id' => $novelty->getIdNovelty(),
	                'units' => $novelty->getUnits(),
	                'dateStart' => $novelty->getDateStart(),
	                'dateEnd' => $novelty->getDateEnd(),
	                'amount' => money_format("$%!i", $novelty->getAmount()),
	                'motivo' => $novelty->getDescription()
	            );

	            $data = array(
	                'employee' => $employeeInfo,
	                'employer' => $employerInfo,
	                'contract' => $contractInfo,
	                'novelty' => $noveltyInfo
	            );
	            break;
	        case "mandato":
	            //$id del empleador
	            $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employer');
                /** @var \RocketSeller\TwoPickBundle\Entity\Employer $employer */
                $employer = $repository->find($id);

	            $employerPerson = $employer->getPersonPerson();
	            $employerInfo = array(
	                'name' => $this->fullName($employerPerson->getIdPerson()),
	                'docType' => $employerPerson->getDocumentType(),
	                'docNumber' => $employerPerson->getDocument(),
	                'docExpPlace' => $employerPerson->getDocumentExpeditionPlace()
	            );
	            $data = array(
	                'employer' => $employerInfo
	            );
	            break;
	        case "factura":
	            //id de la orden de compra
	            $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:PurchaseOrders');
	            /** @var \RocketSeller\TwoPickBundle\Entity\PurchaseOrders $purchaseOrders */
	            $purchaseOrders = $repository->find($id);

	            $purchaseInfo = array(
	                'number' => $purchaseOrders->getIdPurchaseOrders(),
	                'city' => 'bogotá',
	                'endDate' => null,
	                'center' => null,
	                'total' => $purchaseOrders->getValue()
	            );

	            $client = $purchaseOrders->getIdUser()->getPersonPerson();
	            $clientInfo = array(
	                'name' => $this->fullName($client->getIdPerson()),
	                'docType' => $client->getDocumentType(),
	                'docNumber' => $client->getDocument(),
	                'address' => $client->getMainAddress(),
	                'phone' => $client->getPhones()->getValues(),
	                'city' => $client->getCity()->getName()
	            );

	            $descriptions = $purchaseOrders->getPurchaseOrderDescriptions();

	            $productsPrice = 0;
	            /** @var \RocketSeller\TwoPickBundle\Entity\PurchaseOrdersDescription $desc */
	            foreach ($descriptions as $desc) {
	                $items[] = array(
	                    'desc' => $desc->getDescription(),
                        'product' => $desc->getProductProduct(),
	                    'pays' => $desc->getPayPay(),
	                    'status' => $desc->getPurchaseOrdersStatus(),
	                    'totalValue' => $desc->getValue(),
	                    'unitValue' => $desc->getProductProduct()->getPrice()
	                );
	                $productsPrice += $desc->getProductProduct()->getPrice();
	            }

	            $ivaTotal = $purchaseOrders->getValue() - $productsPrice;
	            $purchaseInfo['iva'] = $ivaTotal;
	            $purchaseInfo['subTotal'] = $productsPrice;

	            $data = array(
	                'invoiceNumber' => $purchaseOrders->getInvoiceNumber(),
	                'client' => $clientInfo,
	                'purchaseOrder' => $purchaseInfo,
	                'items' => $items
	            );
    	    default:
    	        break;
        };

	    $template = 'RocketSellerTwoPickBundle:Document:' . $ref . '.html.twig';

	    switch ($type){
    	    case "html":
        	    return $this->render($template, array(
        	        'data' => $data
        	    ));
        	    break;
    	    default:
    	    case "pdf":
    	        $html = $this->renderView($template, array(
    	            'data' => $data
    	        ));

    	        if ($attach) {
    	            if (isset($data["invoiceNumber"]) && $data["invoiceNumber"] != null ) {
            	        $data["invoiceNumber"] = 123;
            	        $docName = $data['client']['docNumber'] . "-" . $data["invoiceNumber"] . ".pdf";
    	            } else {
    	                $docName = $data['client']['docNumber'] . "-" . $data['purchaseOrder']['number'] . ".pdf";
    	            }
        	        $path = $this->get('kernel')->getRootDir(). "/../web/public/docs/tmp/invoices/" . $docName;

        	        if (!file_exists($path)) {

        	             $this->get('knp_snappy.pdf')->generateFromHtml(
        	                $this->renderView(
        	                    $template, array(
        	                        'data' => $data
        	                    )
    	                    ),
        	                $path
    	                );
        	        }

        	        return new JsonResponse(array("name-path" => $docName));
    	        }

                return new Response(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                    200,
                    array(
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $ref . '.pdf"'
                    )
                );
                break;
	    };
	}
}