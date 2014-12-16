<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * ImageSliderAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ImageSliderAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'slide';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Imatge', array('class' => 'col-md-6'))
            ->add('imageFile', 'file', array(
                    'label' => 'Imatge',
                    'required' => false,
                    'help' => $this->getImageHelperFormMapperWithThumbnail(),
                ))
            ->add('link', null, array('label' => 'Enllaç'))
            ->add('metaTitle', null, array('label' => 'Títol (SEO)'))
            ->add('metaAlt', null, array('label' => 'Alt (SEO)'))
            ->end()
            ->with('Traduccions', array('class' => 'col-md-6'))
            ->add('translations', 'a2lix_translations_gedmo', array(
                    'required' => false,
                    'label' => ' ',
                    'translatable_class' => 'FinquesFarnos\AppBundle\Entity\Translation\ImageTranslation',
                    'fields' => array(
                        'metaTitle' => array('label' => 'Títol (SEO)', 'required' => false),
                        'metaAlt' => array('label' => 'Alt (SEO)', 'required' => false),
                    ),
                ))
            ->end()
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
            ->add('imageFile', null, array('label' => 'Imatge', 'template' => '::Admin/slide_list_field.html.twig'))
            ->add('link', null, array('label' => 'Enllaç', 'editable' => true))
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
     * Datagrid list view
     *
     * @var array
     */
    public $datagridValues = array(
        '_sort_by' => 'position',
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

        return ($this->getSubject()->getImageName() ? '<img src="'.$lis->getBrowserPath($vus->asset($this->getSubject(), 'imageFile'), '300xY').'" class="admin-preview" alt=""/>' : '').'<span style="width:100%;display:block;">Màxim 10MB amb format PNG, JPG o GIF. Imatge amb amplada mínima de 1.200px.</span>';
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
