<?php

namespace RocketSeller\TwoPickBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseOrders extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                ),))
            ->add('purchaseOrdersType', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:PurchaseOrdersType',
                'placeholder' => '',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'property_path' => 'purchaseOrdersTypePurchaseOrdersType'
                ))
            ->add('payroll', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:Payroll',
                'placeholder' => '',
                'property' => 'idPayroll',
                'multiple' => false,
                'expanded' => false,
                'required'    => false,
                'property_path' => 'payrollPayroll'
                ))
            ->add('purchaseOrdersStatus', 'entity', array(
                'class' => 'RocketSellerTwoPickBundle:PurchaseOrdersStatus',
                'placeholder' => '',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'property_path' => 'purchaseOrdersStatusPurchaseOrdersStatus'
                ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        	'data_class' => 'RocketSeller\TwoPickBundle\Entity\PurchaseOrders'
        ));
    }

    public function getName()
    {
        return 'register_purchase_orders';
    }
}