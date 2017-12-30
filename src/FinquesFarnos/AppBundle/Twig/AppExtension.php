<?php

namespace FinquesFarnos\AppBundle\Twig;

use FinquesFarnos\AppBundle\Service\InmopcHelperService;

/**
 * Class AppExtension.
 *
 * @category Twig
 *
 * @author   David Romaní <david@flux.cat>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var InmopcHelperService
     */
    private $ihs;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param InmopcHelperService $ihs
     */
    public function __construct(InmopcHelperService $ihs)
    {
        $this->ihs = $ihs;
    }

    /**
     * Functions.
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_inmopc_iframe_src_general', array($this, 'getInmopcIframeSrcGeneral')),
        );
    }

    /**
     * Get Inmopc Iframe source URL for general finder.
     */
    public function getInmopcIframeSrcGeneral()
    {
        return $this->ihs->getInmopcIframeSrcGeneral();
    }
}
