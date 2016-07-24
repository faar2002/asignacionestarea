<?php

namespace User\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('firstName')
            ->add('lastName')
            ->add('email','email')
            ->add('password', 'password')
            ->add('role', 'choice', array('choices' => 
            array('ROLE_ADMIN'=>'Administrator','ROLE_USER'=>'User'),'placeholder' => 'Seleccionar un Rol'))
            ->add('isActive','checkbox')
            //->add('createdAt', 'hidden')
            //->add('updatedAt', 'hidden')
            ->add('save','submit',array('label'=>'Guardar Usuario'))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\UserBundle\Entity\User'
        ));
    }
}
