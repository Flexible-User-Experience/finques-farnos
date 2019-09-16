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
    const URI_IMMOEBRE_CA = 'https://immoebre.com';
    const URI_IMMOEBRE_ES = 'https://immoebre.com/es';
    const URI_IMMOEBRE_EN = 'https://immoebre.com/en';
    const URI_IMMOEBRE_FR = 'https://immoebre.com/fr';

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

        if ('es' == $locale) {
            $result = self::URI_IMMOEBRE_ES;
        } elseif ('en' == $locale) {
            $result = self::URI_IMMOEBRE_EN;
        } elseif ('fr' == $locale) {
            $result = self::URI_IMMOEBRE_FR;
        }

        return $result;
    }
}
