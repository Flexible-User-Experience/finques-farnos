<?php

namespace FinquesFarnos\AppBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FinquesFarnos\AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
            ->add('reference', 'text', array('label' => 'Referència'))
            ->add('name', 'text', array('label' => 'Nom'))
            ->add('description', 'textarea', array('label' => 'Descripció', 'attr' => array('style' => 'height:150px')))
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
            ->with('Propietats', array('class' => 'col-md-6'))
            ->add('price', null, array('label' => 'Preu'))
            ->add('oldPrice', null, array('label' => 'Preu anterior'))
            ->add('rooms', null, array('label' => 'Habitacions', 'required' => false))
            ->add('bathrooms', null, array('label' => 'Banys', 'required' => false))
            ->add('energyClass', 'choice', array('label' => 'Classificació energètica', 'required' => false, 'choices' => array(
                    0 => 'sense clasificació',
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
            ->with('Imatges', array('class' => 'col-md-6'))
            ->add('images', 'sonata_type_model', array(
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'label' => 'Imatges',
                    'btn_add' => false,
                    'disabled' => true,
                    'help' => $this->getImageHelperFormMapperWithThumbnail(),
                ))
            ->end()
            ->with('Geolocalització', array('class' => 'col-md-6'))
            ->add('latLng', 'oh_google_maps', array('label' => 'Mapa', 'required' => false))
            ->add('showMapType', 'choice', array('label' => 'Mostrar mapa', 'required' => true, 'choices' => array(
                    0 => 'mostrar tot',
                    1 => 'mostrar només carrer',
                    2 => 'mostrar només àrea',
                )))
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('showInHomepage', 'checkbox', array('label' => 'Mostrar a la homepage', 'required' => false))
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
            ->add('reference', null, array('label' => 'Ref.', 'editable' => false))
            ->add('name', null, array('label' => 'Immoble', 'editable' => true))
            ->add('categories', null, array('label' => 'Categories'))
            ->add('type', null, array(
                    'label' => 'Tipus',
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

    /**
     * Get image helper form mapper with thumbnail
     *
     * @return string
     */
    private function getImageHelperFormMapperWithThumbnail()
    {
        /** @var CacheManager $lis */
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        /** @var UploaderHelper $vus */
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');
        /** @var ArrayCollection $images */
        $images = $this->getSubject()->getImages();
        if ($images->count() > 0) {
            $result = '';
            /** @var Image $image */
            foreach ($images as $image) {
                $result .= '<img src="' . $lis->getBrowserPath($vus->asset($image, 'property_image'), '60x60') . '" class="admin-preview" style="margin-right:10px" alt="' . $image->getMetaAlt() . '"/>';
            }
            return $result;
        }

        return '';
    }
}
