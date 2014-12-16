<?php

namespace FinquesFarnos\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('name', null, array('label' => 'contact.form.name'))
            ->add('phone', null, array('label' => 'contact.form.phone'))
            ->add('email', 'email', array('label' => 'contact.form.email'))
            ->add('message', 'textarea', array(
                    'label' => 'contact.form.message',
                    'mapped' => false,
                    'attr' => array('rows' => '5'),
                ))
//            ->add('message', new ContactMessageType())
        ;
    }

    /**
     * Set default form options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FinquesFarnos\AppBundle\Entity\Contact',
            ));
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}
