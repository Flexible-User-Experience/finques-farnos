<?php

namespace FinquesFarnos\AppBundle\Service;

/**
 * Class InmopcHelperService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class InmopcHelperService
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
     * InmopcHelperService constructor.
     *
     * @param string $inmopcCustomerCode
     */
    public function __construct($inmopcCustomerCode)
    {
        $this->inmopcCustomerCode = $inmopcCustomerCode;
        $this->inmopcIframeSrcUrl = self::INMOPC_BASE_URL.'cod_cliente='.$inmopcCustomerCode.'&cod_captacion='.$inmopcCustomerCode.'&est_bus=1';
    }

    /**
     * @return string
     */
    public function getInmopcIframeSrcGeneral()
    {
        return $this->inmopcIframeSrcUrl;
    }
}
