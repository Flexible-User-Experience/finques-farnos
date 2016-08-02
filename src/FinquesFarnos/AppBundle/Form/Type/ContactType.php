<?php

namespace FinquesFarnos\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * ContactType class
 *
 * @category FormType
 * @package  FinsquesFarnos\AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class ContactType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => 'contact.form.name'
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label' => 'contact.form.phone'
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'contact.form.email'
                )
            )
            ->add(
                'message',
                TextareaType::class,
                array(
                    'label'  => 'contact.form.message',
                    'mapped' => false,
                    'attr'   => array('rows' => '5'),
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'FinquesFarnos\AppBundle\Entity\Contact',
            )
        );
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contact';
    }
}
