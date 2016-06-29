<?php
namespace RocketSeller\TwoPickBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RocketSeller\TwoPickBundle\Entity\DocumentType;

class LoadDocumentTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $documentTypeCedula = new DocumentType();
        $documentTypeCedula->setName('Cedula');
        $manager->persist($documentTypeCedula);

        $documentTypeComprobante = new DocumentType();
        $documentTypeComprobante->setName('Comprobante');
        $manager->persist($documentTypeComprobante);

        $documentTypeRut = new DocumentType();
        $documentTypeRut->setName('Rut');
        $manager->persist($documentTypeRut);

        $documentTypeContrato = new DocumentType();
        $documentTypeContrato->setName('Contrato');
        $manager->persist($documentTypeContrato);

        $documentTypeCartaAut = new DocumentType();
        $documentTypeCartaAut->setName('Carta autorización Symplifica');
        $manager->persist($documentTypeCartaAut);

        $documentTypeRegistroCiv = new DocumentType();
        $documentTypeRegistroCiv->setName('REGISTRO CIVIL DE NACIMIENTO COTIZANTE');
        $manager->persist($documentTypeRegistroCiv);

        $documentTypeRegistroCivHijo = new DocumentType();
        $documentTypeRegistroCivHijo->setName('REGISTRO CIVIL DE NACIMIENTO HIJO (BENEFICIARIO)');
        $manager->persist($documentTypeRegistroCivHijo);

        $documentLicenciaMaternidad = new DocumentType();
        $documentLicenciaMaternidad->setName('LICENCIA DE MATERNIDAD EN ORGINAL');
        $manager->persist($documentLicenciaMaternidad);

        $documentLicenciaPaternidad = new DocumentType();
        $documentLicenciaPaternidad->setName('LICENCIA DE PATERNIDAD EN ORIGINAL');
        $manager->persist($documentLicenciaPaternidad);

        $documentTypeHistoriaClinica = new DocumentType();
        $documentTypeHistoriaClinica->setName('HISTORIA CLINICA DEL EVENTO');
        $manager->persist($documentTypeHistoriaClinica);

        $documentTypeDocumentoSoporte = new DocumentType();
        $documentTypeDocumentoSoporte->setName('DOCUMENTOS SOPORTES DE LA NOVEDAD');
        $manager->persist($documentTypeDocumentoSoporte);

        $documentTypeFormatoSoporte = new DocumentType();
        $documentTypeFormatoSoporte->setName('FORMATOS SOPORTES DE LA NOVEDAD');
        $manager->persist($documentTypeFormatoSoporte);

        $documentTypeIncapacidad = new DocumentType();
        $documentTypeIncapacidad->setName('INCAPACIDAD');
        $manager->persist($documentTypeIncapacidad);

        $documentTypeReporteAcci = new DocumentType();
        $documentTypeReporteAcci->setName('REPORTE DE ACCIDENTE DE TRABAJO');
        $manager->persist($documentTypeReporteAcci);

        $documentTypeCertificadoDefuncion = new DocumentType();
        $documentTypeCertificadoDefuncion->setName('CERTIFICADO DEFUNCION DE (PADRES, COMPAÑERA, HIJOS, HERMANOS) ');
        $manager->persist($documentTypeCertificadoDefuncion);

        $documentTypeRegistroCivil = new DocumentType();
        $documentTypeRegistroCivil->setName('REGISTRO CIVIL  ( BENEFICIARIO ) ');
        $manager->persist($documentTypeRegistroCivil);

        $documentTypeDocumentoIdent = new DocumentType();
        $documentTypeDocumentoIdent->setName('DOCUMENTO DE IDENTIDAD DE BENEFICIARIO');
        $manager->persist($documentTypeDocumentoIdent);

        $cartaRenuncia = new DocumentType();
        $cartaRenuncia->setName('Carta de renuncia');
        $manager->persist($cartaRenuncia);



        $solVacaciones = new DocumentType();
        $solVacaciones->setName('Solicitud de vacaciones');
        $solVacaciones->setRefPdf('vacaciones');
        $manager->persist($solVacaciones);

        $suspencion = new DocumentType();
        $suspencion->setName('Suspencion');
        $suspencion->setRefPdf('suspencion');
        $manager->persist($suspencion);

        $descargo = new DocumentType();
        $descargo->setName('Version libre de hechos');
        $descargo->setRefPdf('descargo');
        $manager->persist($descargo);

        $descuento = new DocumentType();
        $descuento->setName('Autorización de descuento');
        $descuento->setRefPdf('aut-descuento');
        $manager->persist($descuento);

        $dotacion = new DocumentType();
        $dotacion->setName('Soporte entrega de dotación');
        $dotacion->setRefPdf('dotacion');
        $manager->persist($dotacion);

        $permiso = new DocumentType();
        $permiso->setName('Licencia no remunerada');
        $permiso->setRefPdf('permiso');
        $manager->persist($permiso);

        $notDespido = new DocumentType();
        $notDespido->setName('Notificación de despido');
        $notDespido->setRefPdf('not-despido');
        $manager->persist($notDespido);

        $mandato = new DocumentType();
        $mandato->setName('Mandato');
        $mandato->setRefPdf('mandato');
        $manager->persist($mandato);

        $manager->flush();

        $this->addReference('document-type-cedula', $documentTypeCedula);
        $this->addReference('document-type-comprobante', $documentTypeComprobante);
        $this->addReference('document-type-rut', $documentTypeRut);
        $this->addReference('document-type-contrato', $documentTypeContrato);
        $this->addReference('document-carta-aut', $documentTypeCartaAut);
        $this->addReference('document-registro-civ', $documentTypeRegistroCiv);
        $this->addReference('document-registro-civ-hijo', $documentTypeRegistroCivHijo);
        $this->addReference('document-licencia-maternidad', $documentLicenciaMaternidad);
        $this->addReference('document-licencia-paternidad', $documentLicenciaPaternidad);
        $this->addReference('document-historia-clinica', $documentTypeHistoriaClinica);
        $this->addReference('document-documento-soporte', $documentTypeDocumentoSoporte);
        $this->addReference('document-formato-soporte', $documentTypeFormatoSoporte);
        $this->addReference('document-incapacidad', $documentTypeIncapacidad);
        $this->addReference('document-reporte-acci', $documentTypeReporteAcci);
        $this->addReference('document-certificado-defuncion', $documentTypeCertificadoDefuncion);
        $this->addReference('document-registro-civil', $documentTypeRegistroCivil);
        $this->addReference('document-documento-identidad', $documentTypeDocumentoIdent);
        $this->addReference('document-carta-renuncia', $cartaRenuncia);

        $this->addReference('document-not-despido', $notDespido);
        $this->addReference('document-permiso', $permiso);
        $this->addReference('document-dotacion', $dotacion);
        $this->addReference('document-aut-descuento', $descuento);
        $this->addReference('document-descargo', $descargo);
        $this->addReference('document-suspencion', $suspencion);
        $this->addReference('document-sol-vacaciones', $solVacaciones);
    }
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 5;
    }
}
