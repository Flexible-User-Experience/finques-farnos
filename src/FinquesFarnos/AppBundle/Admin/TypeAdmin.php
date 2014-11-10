<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * TypeAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class TypeAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'type';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Tipus')
            ->add('name', 'text', array('label' => 'Nom'))
            ->add('enabled', 'checkbox', array('label' => 'Actiu', 'required' => false));
    }

    /**
     * List view
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('label' => 'Tipus', 'editable' => true))
            ->add('enabled', null, array('label' => 'Actiu', 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                    ),
                    'label' => 'Accions',
                ));
    }

    /**
     * Export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array();
    }
}
