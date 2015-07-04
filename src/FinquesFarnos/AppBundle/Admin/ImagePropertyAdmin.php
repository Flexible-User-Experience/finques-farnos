<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * ImagePropertyAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ImagePropertyAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'image';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $imageHelp = $this->getImageHelperFormMapperWithThumbnail();
        $formMapper
            ->with('Imatge', array('class' => 'col-md-6'))
            ->add('property', null, array(
                    'attr' => array('hidden' => true)
                ))
            ->add('imageFile', 'file', array(
                    'label' => 'Imatge',
                    'required' => false,
                    'sonata_help' => $imageHelp, // use 'sonata_help' when it is included by a parent relationship
                    'help' => $imageHelp,
                ))
//            ->add('metaTitle', null, array('label' => 'Títol (SEO)'))
            ->add('metaAlt', null, array('label' => 'Alt (SEO)'))
            ->end()
//            ->with('Traduccions', array('class' => 'col-md-6'))
//            ->add('translations', 'a2lix_translations_gedmo', array(
//                    'required' => false,
//                    'label' => ' ',
//                    'translatable_class' => 'FinquesFarnos\AppBundle\Entity\Translation\ImageTranslation',
//                    'fields' => array(
//                        'metaTitle' => array('label' => 'Títol (SEO)', 'required' => false),
//                        'metaAlt' => array('label' => 'Alt (SEO)', 'required' => false),
//                    ),
//                ))
//            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('position', null, array('label' => 'Posició', 'required' => true))
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
            ->add('imageFile', null, array('label' => 'Imatge', 'template' => '::Admin/image_list_field.html.twig'))
            ->addIdentifier('property', null, array(
                    'label' => 'Immoble',
                    'editable' => false,
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'property')),
                ))
//            ->add('metaTitle', null, array('label' => 'Títol (SEO)', 'editable' => false))
            ->add('metaAlt', null, array('label' => 'Alt (SEO)', 'editable' => true))
            ->add('position', null, array('label' => 'Posició', 'editable' => true))
            ->add('enabled', null, array('label' => 'Activa', 'editable' => true))
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
            ->add('property', null, array('label' => 'Immoble'), null, array(
                    'expanded' => false,
                    'multiple' => true,
                ))
            ->add('position', null, array('label' => 'Posició'))
            ->add('enabled', null, array('label' => 'Activa'));
    }

    /**
     * Datagrid list view
     *
     * @var array
     */
    public $datagridValues = array(
        '_sort_by' => 'property.name',
    );

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

        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="'.$lis->getBrowserPath($vus->asset($this->getSubject(), 'imageFile'), '100x60').'" class="admin-preview" alt=""/>' : '' : '');
    }
}
