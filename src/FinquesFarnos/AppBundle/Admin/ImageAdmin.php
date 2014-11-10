<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * ImageAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ImageAdmin extends BaseAdmin
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
        $formMapper
            ->with('Imatge', array('class' => 'col-md-6'))
            ->add('imageFile', 'file', array(
                    'label' => 'Imatge',
                    'required' => false,
                    'help' => $this->getImageHelperFormMapperWithThumbnail(),
                ))
            ->add('metaTitle', null, array('label' => 'Títol (SEO)'))
            ->add('metaAlt', null, array('label' => 'Alt (SEO)'))
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
            ->add('imageFile', null, array('label' => 'Imatge', 'template' => '::Admin/image_list_field.html.twig'))
            ->add('metaTitle', null, array('label' => 'Títol (SEO)', 'editable' => false))
            ->add('metaAlt', null, array('label' => 'Alt (SEO)', 'editable' => false))
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
            ->add('position', null, array('label' => 'Posició'))
            ->add('enabled', null, array('label' => 'Activa'));
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
        return ($this->getSubject()->getImageName() ? '<img src="' . $lis->getBrowserPath($vus->asset($this->getSubject(), 'property_image'), '300xY') . '" class="admin-preview" alt=""/>' : '') . '<span style="width:100%;display:block;">Màxim 10MB amb format PNG, JPG o GIF. Imatge amb amplada mínima de 1.200px.</span>';
    }
}
