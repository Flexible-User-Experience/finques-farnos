<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * PropertyAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class PropertyAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'property';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Immoble', array('class' => 'col-md-6'))
            ->add('name', 'text', array('label' => 'Nom'))
            ->add('description', 'textarea', array('label' => 'Descripció'))
            ->add('categories', 'sonata_type_model', array(
                    'required' => true,
                    'expanded' => false,
                    'multiple' => true,
                    'label' => 'Categories',
                    'btn_add' => true,
                ))
            ->add('type', 'sonata_type_model', array(
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Tipus',
                    'btn_add' => true,
                ))
            ->add('price', null, array('label' => 'Preu'))
            ->add('oldPrice', null, array('label' => 'Preu anterior'))
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('rooms', null, array('label' => 'Habitacions', 'required' => false))
            ->add('bathrooms', null, array('label' => 'Banys', 'required' => false))
            ->add('offerDiscount', 'checkbox', array('label' => 'Oferta descompte', 'required' => false))
            ->add('offerSpecial', 'checkbox', array('label' => 'Oferta especial', 'required' => false))
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
        $listMapper
            ->add('name', null, array('label' => 'Immoble', 'editable' => true))
            ->add('categories', null, array('label' => 'Categories', 'editable' => true))
            ->addIdentifier('type', null, array(
                    'label' => 'Tipus',
                    'editable' => false,
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'type')),
                ))
            ->add('price', 'integer', array('label' => 'Preu', 'editable' => true))
            ->add('imagesCount', 'integer', array('label' => 'Imatges', 'template' => '::Admin/property_images_count_list_field.html.twig'))
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
            ->add('name', null, array('label' => 'Immoble'))
            ->add('categories', null, array('label' => 'Categories'), null, array(
                    'expanded' => false,
                    'multiple' => true,
                ))
            ->add('type', null, array('label' => 'Tipus'), null, array(
                    'expanded' => false,
                    'multiple' => true,
                ))
            ->add('price', null, array('label' => 'Preu'))
            ->add('enabled', null, array('label' => 'Actiu'));
    }

    /**
     * Available routes
     *
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}
