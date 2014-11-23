<?php

namespace FinsquesFarnos\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CategoryType class
 *
 * @category FormType
 * @package  FinsquesFarnos\AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class CategoryType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
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
