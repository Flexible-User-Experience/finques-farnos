<?php

namespace FinquesFarnos\AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var RequestStack
     */
    private $rs;

    /**
     * Methods.
     */

    /**
     * InmopcHelperService constructor.
     *
     * @param RequestStack $rs
     */
    public function __construct(RequestStack $rs)
    {
        $$this->rs = $rs;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $result = self::URI_IMMOEBRE_CA;

        if ($this->rs->getCurrentRequest()->getLocale() == 'es') {
            $result = self::URI_IMMOEBRE_ES;
        } elseif ($this->rs->getCurrentRequest()->getLocale() == 'en') {
            $result = self::URI_IMMOEBRE_EN;
        } elseif ($this->rs->getCurrentRequest()->getLocale() == 'fr') {
            $result = self::URI_IMMOEBRE_FR;
        }

        return $result;
    }
}
