<?php

namespace FinquesFarnos\AppBundle\Service;

/**
 * Class InmoebreUriLocaleService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class InmoebreUriLocaleService
{
    const URI_IMMOEBRE_CA = 'http://www.immoebre.com/catalan/index.html';
    const URI_IMMOEBRE_ES = 'http://www.immoebre.com/index.html';
    const URI_IMMOEBRE_EN = 'http://www.immoebre.com/ingles/index.html';
    const URI_IMMOEBRE_FR = 'http://www.immoebre.com/frances/index.html';

    /**
     * Methods.
     */

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getUriFromLocale($locale = 'ca')
    {
        $result = self::URI_IMMOEBRE_CA;

        if ($locale == 'es') {
            $result = self::URI_IMMOEBRE_ES;
        } elseif ($locale == 'en') {
            $result = self::URI_IMMOEBRE_EN;
        } elseif ($locale == 'fr') {
            $result = self::URI_IMMOEBRE_FR;
        }

        return $result;
    }
}
