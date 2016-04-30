<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * CustomerAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class CustomerAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'customer';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Client', array('class' => 'col-md-6'))
            ->add('dni', null, array('label' => 'DNI'))
            ->add('name', null, array('label' => 'Nom'))
            ->add('phone', null, array('label' => 'Telèfon'))
            ->add('mobile', null, array('label' => 'Mòbil'))
            ->add('email', null, array('label' => 'Email'))
            ->end()
            ->with('Adreça', array('class' => 'col-md-6'))
            ->add('address', null, array('label' => 'Adreça'))
            ->add('city', null, array('label' => 'Població'))
            ->add('postalCode', null, array('label' => 'Codi postal'))
            ->add('province', null, array('label' => 'Provincia'))
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('enabled', 'checkbox', array('label' => 'Actiu', 'required' => false))
            ->end();
    }

    /**
     * List view
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add('dni', null, array('label' => 'DNI', 'editable' => true))
            ->add('name', null, array('label' => 'Nom', 'editable' => true))
            ->add('phone', null, array('label' => 'Telèfon', 'editable' => true))
            ->add('mobile', null, array('label' => 'Mòbil', 'editable' => true))
            ->add('email', null, array('label' => 'Email', 'editable' => true))
            ->add('enabled', null, array('label' => 'Actiu', 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                    ),
                    'label' => 'Accions',
                ));
    }

    /**
     * List filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('dni', null, array('label' => 'Dni'))
            ->add('name', null, array('label' => 'Nom'))
            ->add('email', null, array('label' => 'Email'))
            ->add('enabled', null, array('label' => 'Actiu'));
    }
}
