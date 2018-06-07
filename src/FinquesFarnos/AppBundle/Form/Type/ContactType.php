<?php

namespace FinquesFarnos\AppBundle\Form\Type;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * ContactType class.
 *
 * @category FormType
 *
 * @author   David Romaní <david@flux.cat>
 */
class ContactType extends AbstractType
{
    /**
     * Build form.
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
                    'label' => 'contact.form.name',
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label' => 'contact.form.phone',
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'contact.form.email',
                )
            )
            ->add(
                'message',
                TextareaType::class,
                array(
                    'label' => 'contact.form.message',
                    'mapped' => false,
                    'attr' => array('rows' => '4'),
                )
            )
            ->add(
                'privacy',
                CheckboxType::class,
                array(
                    'label' => 'contact.form.privacy',
                    'mapped' => false,
                    'required' => true,
                )
            )
            ->add(
                'captcha',
                EWZRecaptchaType::class,
                array(
                    'label' => ' ',
                    'attr' => array(
                        'options' => array(
                            'theme' => 'light',
                            'type' => 'image',
                            'size' => 'normal',
                        ),
                    ),
                    'mapped' => false,
                    'constraints' => array(
                        new RecaptchaTrue(),
                    ),
                )
            )
        ;
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
     * Get form name.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contact';
    }
}
