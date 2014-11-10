<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * CategoryAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class CategoryAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'category';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Categoria', array('class' => 'col-md-6'))
            ->add('name', 'text', array('label' => 'Nom'))
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('enabled', 'checkbox', array('label' => 'Activa', 'required' => false))
            ->end();
    }

    /**
     * List view
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('label' => 'Categoria', 'editable' => true))
            ->add('enabled', null, array('label' => 'Activa', 'editable' => true))
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
