<?php

namespace FinquesFarnos\AppBundle\Controller\Admin;

use FinquesFarnos\AppBundle\PdfGenerator\PropertyGenerator;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        /** @var PropertyGenerator $generator */
        $generator = $this->get('app.property_pdf_generator');
        $pdf = $generator->generate(array('property' => $object));

        return new Response($pdf->getContents(), 200, array('Content-type' => 'application/pdf'));
    }
}
