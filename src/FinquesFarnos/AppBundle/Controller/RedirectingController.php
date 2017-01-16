<?php

namespace FinquesFarnos\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class RedirectingController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class RedirectingController extends Controller
{
    /**
     * @Route("/{url}", name="remove_trailing_slash", requirements={"url"=".*\/$"}, methods={"GET"}, options={"sitemap"=false, "expose"=false})
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeTrailingSlashAction(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);

        return $this->redirect($url, 301);
    }
}
