<?php

namespace FinquesFarnos\AppBundle\Twig;

use FinquesFarnos\AppBundle\Service\InmoebreUriLocaleService;
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
     * @var InmoebreUriLocaleService
     */
    private $iuls;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param InmopcHelperService      $ihs
     * @param InmoebreUriLocaleService $iuls
     */
    public function __construct(InmopcHelperService $ihs, InmoebreUriLocaleService $iuls)
    {
        $this->ihs = $ihs;
        $this->iuls = $iuls;
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
            new \Twig_SimpleFunction('get_immoebre_uri_locale', array($this, 'getImmoebreUriLocale')),
        );
    }

    /**
     * Get Inmopc Iframe source URL for general finder.
     *
     * @return string
     */
    public function getInmopcIframeSrcGeneral()
    {
        return $this->ihs->getInmopcIframeSrcGeneral();
    }

    /**
     * Get Immobere URI from locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getImmoebreUriLocale($locale)
    {
        return $this->iuls->getUriFromLocale($locale);
    }
}
