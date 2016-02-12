<?php

namespace RocketSeller\TwoPickBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class LiquidationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('daysToLiquidate')
            ->add('lastWorkDay', 'date', array(
                'data' => new DateTime('1975-01-01'),
                'years' => range(1900, 2015),
                'constraints' => array(
                    new NotBlank()
                ),
                'label' => 'Último día de trabajo de tu empleado'
            ))
            ->add('cost')
            ->add('idPurchaseOrder', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:PurchaseOrders',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'property_path' => 'purchase_order',
                'placeholder' => 'Seleccionar una opción',
                'required' => false
            ))
            ->add('contract', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:Contract',
                'property' => 'idContract',
                'multiple' => false,
                'expanded' => false,
                'property_path' => 'contract',
                'placeholder' => 'Seleccionar una opción',
                'required' => false
            ))
            ->add('liquidationType', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:LiquidationType',
                'property' => 'name'
            ))
            ->add('liquidationReason', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:LiquidationReason',
                'property' => 'name',
                'multiple' => false,
                'expanded' => true
            ))
            ->add('employerHasEmployee', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:EmployerHasEmployee',
                'property' => 'idEmployerHasEmployee'
            ))
            ->add('save', 'submit', array(
                'attr'   =>  array(
                    'class'   => 'btn btn-primary',
                    'disabled' => true
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RocketSeller\TwoPickBundle\Entity\Liquidation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rocketseller_twopickbundle_liquidation';
    }
}