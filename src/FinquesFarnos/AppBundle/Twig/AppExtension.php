<?php

namespace FinquesFarnos\AppBundle\Twig;

/**
 * Class AppExtension.
 *
 * @category Twig
 *
 * @author   David Romaní <david@flux.cat>
 */
class AppExtension extends \Twig_Extension
{
    const INMOPC_BASE_URL = 'https://www.forocasas.com/recursos/nlistadom1.php?';

    /**
     * @var string
     */
    private $inmopcCustomerCode;

    /**
     * @var string
     */
    private $inmopcIframeSrcUrl;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param string $inmopcCustomerCode
     */
    public function __construct($inmopcCustomerCode)
    {
        $this->inmopcCustomerCode = $inmopcCustomerCode;
        $this->inmopcIframeSrcUrl = self::INMOPC_BASE_URL.'cod_cliente='.$inmopcCustomerCode.'&cod_captacion='.$inmopcCustomerCode.'&est_bus=1';
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
        return $this->inmopcIframeSrcUrl;
    }
}
