<?php

namespace FinquesFarnos\AppBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FinquesFarnos\AppBundle\Entity\Property;
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
     * Datagrid list view
     *
     * @var array
     */
    public $datagridValues = array(
        '_sort_by' => 'reference',
    );

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Immoble', array('class' => 'col-md-6'))
            ->add('reference', 'text', array('label' => 'Referència'))
            ->add('name', 'text', array('label' => 'Nom'))
            ->add('description', 'textarea', array('label' => 'Descripció', 'attr' => array('rows' => 8)))
            ->add('categories', 'sonata_type_model', array(
                    'required' => true,
                    'expanded' => false,
                    'multiple' => true,
                    'label' => 'Categories',
                    'btn_add' => false,
                ))
            ->add('type', 'sonata_type_model', array(
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Tipus',
                    'btn_add' => false,
                ))
            ->end()
            ->with('Traduccions', array('class' => 'col-md-6'))
            ->add('translations', 'a2lix_translations_gedmo', array(
                    'required' => false,
                    'label' => ' ',
                    'translatable_class' => 'FinquesFarnos\AppBundle\Entity\Translation\PropertyTranslation',
                    'fields' => array(
                        'name' => array('label' => 'Nom', 'required' => false),
                        'description' => array('label' => 'Descripció', 'attr' => array('rows' => 8), 'required' => false),
                    ),
                ))
            ->end()
            ->with('Atributs', array('class' => 'col-md-3'))
            ->add('squareMeters', null, array('label' => 'Metres cuadrats'))
            ->add('price', null, array('label' => 'Preu'))
            ->add('oldPrice', null, array('label' => 'Preu anterior'))
            ->add('rooms', null, array('label' => 'Habitacions', 'required' => false))
            ->add('bathrooms', null, array('label' => 'Banys', 'required' => false))
            ->add('energyClass', 'choice', array('label' => 'Classificació energètica', 'required' => true, 'choices' => array(
                    0 => 'no requereix',
                    1 => 'en tràmit',
                    2 => 'A',
                    3 => 'B',
                    4 => 'C',
                    5 => 'D',
                    6 => 'E',
                    7 => 'F',
                    8 => 'G',
                )))
            ->end()
            ->with('Controls', array('class' => 'col-md-3'))
            ->add('showInHomepage', 'checkbox', array('label' => 'Mostrar l\'immoble a la homepage', 'required' => false))
            ->add('showPriceOnlyWithNumbers', 'checkbox', array('label' => 'Mostrar el preu només amb números', 'required' => false))
            ->add('hidePrice', 'checkbox', array('label' => 'Mostrar el text \'consultar\' enlloc del preu', 'required' => false))
            ->add('offerDiscount', 'checkbox', array('label' => 'Mostrar marca d\'oferta amb descompte', 'required' => false))
            ->add('offerSpecial', 'checkbox', array('label' => 'Mostrar marca d\'oferta amb preu rebaixat', 'required' => false))
            ->add('enabled', 'checkbox', array('label' => 'Actiu', 'required' => false))
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Imatges', array('class' => 'col-md-12'))
                ->add('images', 'sonata_type_collection', array(
                        'cascade_validation' => true,
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable'  => 'position',
                    ))
                ->end();
            $formMapper->setHelps(array('images' => 'Màxim 10MB amb format PNG, JPG o GIF. Imatge amb amplada mínima de 1.200px'));
        }
        $formMapper
            ->with('Geolocalització', array('class' => 'col-md-6'))
            ->add('latLng', 'oh_google_maps', array('label' => 'Mapa', 'required' => false))
            ->add('radius', null, array('label' => 'Radi àrea (m)'))
            ->add('address', null, array('label' => 'Adreça'))
            ->add('city', 'sonata_type_model', array(
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Població',
                    'btn_add' => 'Crear nova població',
                ))
            ->add('showMapType', 'choice', array('label' => 'Al mostrar el mapa de l\'immoble', 'required' => true, 'choices' => array(
                    Property::SHOW_MAP_ALL => 'marcar el punt exacte i mostrar l\'adreça',
                    Property::SHOW_MAP_STREET => 'mostrar l\'adreça sense marcar cap punt',
                    Property::SHOW_MAP_AREA => 'dibuixar una àrea segons el radi sense mostrar l\'adreça',
                )))
            ->end()
            ->with('Venda', array('class' => 'col-md-6'))
            ->add('reserved', 'checkbox', array('label' => 'Reservat', 'required' => false))
            ->add('sold', 'checkbox', array('label' => 'Venut', 'required' => false))
            ->add('soldAt', 'genemu_jquerydate', array('label' => 'Data de venda', 'widget' => 'single_text', 'required' => false))
            ->add('customer', 'sonata_type_model', array(
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Client',
                    'btn_add' => 'Crear nou client',
                ))
            ->end()
            ->with('Visites', array('class' => 'col-md-6'))
            ->add('totalVisits', null, array(
                    'label' => 'Visites totals',
                    'required' => false,
                    'disabled' => true,
                    'help' => $this->getVisitsHelperFormMapper(),
                ))
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
            ->add('reference', null, array('label' => 'Ref.', 'editable' => true))
            ->add('name', null, array('label' => 'Immoble', 'editable' => true))
            ->add('categories', null, array(
                'label' => 'Categories',
                'associated_property' => 'name',
                'sortable' => true,
                'sort_field_mapping' => array('fieldName' => 'name'),
                'sort_parent_association_mappings' => array(array('fieldName' => 'categories')),
            ))
            ->add('type', null, array(
                    'label' => 'Tipus',
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'type')),
                ))
            ->add('price', 'integer', array('label' => 'Preu', 'editable' => true))
            ->add('imagesCount', 'integer', array('label' => 'Imatges', 'template' => '::Admin/property_images_count_list_field.html.twig'))
            ->add('totalVisits', 'integer', array('label' => 'Visites', 'editable' => false))
            ->add('enabled', null, array('label' => 'Actiu', 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array('label' => ' '),
                        'visit' => array('template' => '::Admin/list__action_property_visit_button.html.twig'),
                        'pdf' => array('template' => '::Admin/list__action_property_pdf_button.html.twig'),
                        'delete' => array(),
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
            ->add('reference', null, array('label' => 'Referència'))
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
            ->add('totalVisits', null, array('label' => 'Visites'))
            ->add('reserved', null, array('label' => 'Reservat'))
            ->add('sold', null, array('label' => 'Venut'))
            ->add('enabled', null, array('label' => 'Actiu'));
    }

    /**
     * Available routes
     *
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        //$collection->remove('delete');
        //$collection->remove('batch');
        $collection->remove('show');
        $collection->add('visit', $this->getRouterIdParameter().'/visit');
        $collection->add('pdf', $this->getRouterIdParameter().'/pdf');
        $collection->add('removeImage', $this->getRouterIdParameter().'/remove-image/{iid}');
    }

    /**
     * Get Visits Helper Form Mapper
     *
     * @return string
     */
    private function getVisitsHelperFormMapper()
    {
        /** @var ArrayCollection $visits */
        $visits = $this->getSubject()->getVisits();
        if ($visits->count() > 0) {
            return '<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapsedVisitsList" data-aria-expanded="false" data-aria-controls="collapseExample">Mostrar llistat de visites</button><div class="collapse" id="collapsedVisitsList"><div class="well">' . implode(' ||| ', $visits->toArray()) . '</div></div>';
        }

        return '';
    }
}
