<?php

namespace FinquesFarnos\AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * PropertyAdminController class
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller\Admin
 * @author   David Romaní <david@flux.cat>
 */
class PropertyAdminController extends Controller
{
    /**
     * Render PDF porperty and send HTTP response with file attachment
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function pdfAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with ID: %s', $id));
        }

//        /** @var QrCodeManager $qrManager */
//        $qrManager = $this->get('museums_admin.qr_code_manager');
//        $filename = $qrManager->renderQrImageAndGetFilePath($object);

        $response = new Response();
//        $response->headers->set('Content-Type', 'image/png');
//        $response->headers->set('Content-Disposition', 'attachment; filename=' . basename($filename));
//        $response->headers->set('Content-Length', filesize($filename));
//        $response->headers->set('Pragma', 'public');
//        $response->headers->set('Cache-Control', 'maxage=1');
//        $response->setContent(readfile($filename));

        return $response;
    }
}
