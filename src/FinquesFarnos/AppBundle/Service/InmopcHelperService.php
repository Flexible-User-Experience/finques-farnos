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

    const INMOPC_LOCALE_ES = 1;
    const INMOPC_LOCALE_EN = 2;
    const INMOPC_LOCALE_DE = 3;
    const INMOPC_LOCALE_FR = 4;
    const INMOPC_LOCALE_CA = 5;
    const INMOPC_LOCALE_RU = 8;

    const INMOPC_TYPE_HOUSE = 'G1';
    const INMOPC_TYPE_OFFICE = 'G3';
    const INMOPC_TYPE_SHIP = 'G4';
    const INMOPC_TYPE_GARAGE = 'G5';
    const INMOPC_TYPE_SOLAR = 'G6';
    const INMOPC_TYPE_COUNTRY = 'G8';

    const INMOPC_COLOR_ORANGE = 'D86F24';
    const INMOPC_COLOR_ORANGE_LIGHT = 'F9F0E8';
    const INMOPC_COLOR_ORANGE_DARK = 'E6C3A6';
    const INMOPC_COLOR_GREY_LOGO = '655B45';
    const INMOPC_COLOR_GREY = '424242';
    const INMOPC_COLOR_WHITE = 'FFFFFF';
    const INMOPC_COLOR_BLACK = '000000';

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
        $this->inmopcIframeSrcUrl = self::INMOPC_BASE_URL.'cod_cliente='.$inmopcCustomerCode.'&cod_captacion='.$inmopcCustomerCode.'&est_bus=1&colorfondo='.self::INMOPC_COLOR_ORANGE_LIGHT.'&colorfondo2='.self::INMOPC_COLOR_ORANGE.'&colortitulo='.self::INMOPC_COLOR_BLACK.'&bg_buscador='.self::INMOPC_COLOR_ORANGE;
    }

    /**
     * @return string
     */
    public function getInmopcIframeSrcGeneral()
    {
        return $this->inmopcIframeSrcUrl;
    }
}
