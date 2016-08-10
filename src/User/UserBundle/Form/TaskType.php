<?php

namespace User\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('user', 'entity', array(
                'class' => 'UserUserBundle:User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.role = :only')
                        ->setParameter('only', 'ROLE_USER');
                },
                'choice_label' => 'getFullName'
            ))
            //->add('status')
            //->add('createdAt', 'datetime')
            //->add('updatedAt', 'datetime')
            //->add('user')
            ->add('save', 'submit', array('label' => 'Save task'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\UserBundle\Entity\Task'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'task';
    }
}
