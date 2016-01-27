<?php
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace RocketSeller\TwoPickBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RocketSeller\TwoPickBundle\Entity\ActionType;

class LoadActionTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $actionTypeRevisarRegistro = new ActionType();
        $actionTypeRevisarRegistro->setName('Revisar registro');        
        $manager->persist($actionTypeRevisarRegistro);

        $actionTypeLlevarDocs = new ActionType();
        $actionTypeLlevarDocs->setName('llevar documentos a entidad');        
        $manager->persist($actionTypeLlevarDocs);

        $actionTypeInscripcion = new ActionType();
        $actionTypeInscripcion->setName('Inscripcion');        
        $manager->persist($actionTypeInscripcion);

        $actionTypeLlamarEntidad = new ActionType();
        $actionTypeLlamarEntidad->setName('Llamar entidad');        
        $manager->persist($actionTypeLlamarEntidad);

        $actionTypeLlamarCliente = new ActionType();
        $actionTypeLlamarCliente->setName('Llamar cliente');        
        $manager->persist($actionTypeLlamarCliente);

        
        $manager->flush();

        $this->addReference('actionType-revisar', $actionTypeRevisarRegistro);
        $this->addReference('actionType-llevarDocs', $actionTypeLlevarDocs);
        $this->addReference('actionType-inscripcion', $actionTypeInscripcion);
        $this->addReference('actionType-llamarEntidad', $actionTypeLlamarEntidad);
        $this->addReference('actionType-llamarCliente', $actionTypeLlamarCliente);

    }
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 6;
    }
}
    