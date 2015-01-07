<?php

namespace FinquesFarnos\AppBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FinquesFarnos\AppBundle\Entity\ImageProperty;
use FinquesFarnos\AppBundle\Entity\Property;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

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
            ->with('Propietats', array('class' => 'col-md-3'))
            ->add('squareMeters', null, array('label' => 'Metres cuadrats'))
            ->add('price', null, array('label' => 'Preu'))
            ->add('oldPrice', null, array('label' => 'Preu anterior'))
            ->add('rooms', null, array('label' => 'Habitacions', 'required' => false))
            ->add('bathrooms', null, array('label' => 'Banys', 'required' => false))
            ->add('energyClass', 'choice', array('label' => 'Classificació energètica', 'required' => true, 'choices' => array(
                    0 => 'sense classificació',
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
        }
        $formMapper
            ->with('Geolocalització', array('class' => 'col-md-6'))
            ->add('latLng', 'oh_google_maps', array('label' => 'Mapa', 'required' => false))
            ->add('radius', null, array('label' => 'Radi àrea (m)'))
            ->add('address', null, array('label' => 'Adreça'))
            ->add('city', 'sonata_type_model', array(
                    'required' => false,
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
        $listMapper
            ->add('reference', null, array('label' => 'Ref.', 'editable' => false))
            ->add('name', null, array('label' => 'Immoble', 'editable' => true))
            ->add('categories', null, array('label' => 'Categories'))
            ->add('type', null, array(
                    'label' => 'Tipus',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'type')),
                ))
            ->add('squareMeters', 'integer', array('label' => 'Metres cuadrats', 'editable' => true))
            ->add('price', 'integer', array('label' => 'Preu', 'editable' => true))
            ->add('imagesCount', 'integer', array('label' => 'Imatges', 'template' => '::Admin/property_images_count_list_field.html.twig'))
            ->add('totalVisits', 'integer', array('label' => 'Visites', 'editable' => false))
            ->add('enabled', null, array('label' => 'Actiu', 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'visit' => array('template' => '::Admin/list__action_property_visit_button.html.twig'),
                        'pdf' => array('template' => '::Admin/list__action_property_pdf_button.html.twig'),
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
            ->add('squareMeters', null, array('label' => 'Metres cuadrats'))
            ->add('price', null, array('label' => 'Preu'))
            ->add('totalVisits', null, array('label' => 'Visites'))
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
        $collection->add('visit', $this->getRouterIdParameter().'/visit');
        $collection->add('pdf', $this->getRouterIdParameter().'/pdf');
        $collection->add('removeImage', $this->getRouterIdParameter().'/remove-image/{iid}');
    }

    /**
     * Get image helper form mapper with thumbnail
     *
     * @return string
     */
    private function getImageHelperFormMapperWithThumbnail()
    {
        /** @var Router $rs */
        $rs = $this->getConfigurationPool()->getContainer()->get('router');
        /** @var CacheManager $lis */
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        /** @var UploaderHelper $vus */
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');
        /** @var ArrayCollection $images */
        $images = $this->getSubject()->getImages();
        if ($images->count() > 0) {
            $result = '<div class="images-wrapper" style="float:left;margin-bottom:40px">';
            /** @var ImageProperty $image */
            foreach ($images as $image) {
                $result .= '<div class="image-panel-wrapper" style="float:left;position:relative"><span><a href="'.$rs->generate('admin_finquesfarnos_app_imageproperty_edit', array('id' => $image->getId())).'" class="btn btn-success btn-sm sonata-ba-action" style="position:absolute" title="edita"><i class="fa fa-pencil"></i></a></span><span><a href="'.$rs->generate('admin_finquesfarnos_app_property_removeImage', array('id' => $this->getSubject()->getId(), 'iid' => $image->getId())).'" class="btn btn-success btn-sm sonata-ba-action" style="position:absolute;left:69px" title="esborra"><i class="fa fa-times"></i></a></span><img src="'.$lis->getBrowserPath($vus->asset($image, 'imageFile'), '100x100').'" class="admin-preview" style="margin:0 10px 10px 0;float:left" alt="'.$image->getMetaAlt().'"/></div>';
            }

            return $result.'</div>';
        }

        return '';
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
