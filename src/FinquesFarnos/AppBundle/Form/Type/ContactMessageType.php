<?php

namespace FinquesFarnos\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * ContactMessageType class
 *
 * @category FormType
 * @package  FinsquesFarnos\AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class ContactMessageType extends AbstractType
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
                'text',
                TextareaType::class,
                array(
                    'attr' => array('rows' => '5'),
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FinquesFarnos\AppBundle\Entity\ContactMessage',
            ));
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'message';
    }
}
