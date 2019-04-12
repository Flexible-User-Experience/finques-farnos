<?php

namespace FinquesFarnos\AppBundle\Controller\Admin;

use Cocur\Slugify\Slugify;
use FinquesFarnos\AppBundle\Entity\ImageProperty;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\PdfGenerator\PropertyShowcasePdfGenerator;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PropertyAdminController class.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class PropertyAdminController extends Controller
{
    /**
     * Show public web resource without add a visit record.
     *
     * @param int $id property ID
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function visitAction($id)
    {
        /** @var Property $property */
        $property = $this->admin->getObject($id);
        if (!$property) {
            throw new NotFoundHttpException(sprintf('unable to find property with ID: %s', $id));
        }

        return $this->redirect($this->generateUrl('front_property', array(
                    'type' => $property->getType()->getNameSlug(),
                    'city' => $property->getCity()->getNameSlug(),
                    'name' => $property->getNameSlug(),
                    'reference' => $property->getReference(),
                )));
    }

    /**
     * Render PDF porperty and send HTTP response with file attachment.
     *
     * @param int $id property ID
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function pdfAction($id)
    {
        /** @var Property $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with ID: %s', $id));
        }
        /** @var PropertyShowcasePdfGenerator $generator */
        $generator = $this->get('app.property_showcase_pdf_generator');
        $pdf = $generator->generate(array('property' => $object));

        /** @var Slugify $slugger */
        $slugger = $this->get('sonata.core.slugify.cocur');

        return new Response($pdf->getNativeObject()->Output($slugger->slugify($object->getReference()).'.pdf', 'I'), 200, array('Content-type' => 'application/pdf'));
    }

    /**
     * Remove image from property.
     *
     * @param int $id  property ID
     * @param int $iid image ID
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function removeImageAction($id, $iid)
    {
        /** @var Property $property */
        $property = $this->admin->getObject($id);
        if (!$property) {
            throw new NotFoundHttpException(sprintf('unable to find property with ID: %s', $id));
        }
        /** @var ImageProperty $image */
        $image = $this->getDoctrine()->getRepository('AppBundle:ImageProperty')->find($iid);
        if (!$image) {
            throw new NotFoundHttpException(sprintf('unable to find image property with ID: %s', $iid));
        }
        $this->getDoctrine()->getManager()->remove($image);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('../edit');
    }
}
