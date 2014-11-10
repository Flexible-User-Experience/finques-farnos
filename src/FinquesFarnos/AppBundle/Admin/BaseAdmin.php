<?php

namespace FinquesFarnos\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Abstract BaseAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
abstract class BaseAdmin extends Admin
{
    /**
     * Datagrid list view
     *
     * @var array
     */
    public $datagridValues = array(
        '_sort_by' => 'name',
    );

    /**
     * Available routes
     *
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');
        $collection->remove('delete');
        $collection->remove('show');
    }

    /**
     * Export formats control
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array('xls', 'csv');
    }

    /**
     * Get TinyMce attr array
     *
     * @return array
     */
    protected function getTinyMceAttrArray()
    {
        return array(
            'class' => 'tinymce',
            'data-theme' => 'simple',
            'style' => 'height:300px',
        );
    }
}
