<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use Orkestra\Bundle\PdfBundle\Generator\AbstractPdfGenerator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FinquesFarnos\AppBundle\Entity\Property;

/**
 * PropertyWebPdfGenerator class
 *
 * @category PdfGenerator
 * @package  FinquesFarnos\AppBundle\PdfGenerator
 * @author   David Romaní <david@flux.cat>
 */
class PropertyWebPdfGenerator extends AbstractPdfGenerator
{
    /**
     * Performs the PDF generation
     *
     * @param array $parameters An array of parameters to be used to render the PDF
     * @param array $options    An array of options to be passed to the underlying PdfFactory
     *
     * @return \Orkestra\Bundle\PdfBundle\Pdf\PdfInterface
     */
    protected function doGenerate(array $parameters, array $options)
    {
        /** @var Property $property */
        $property = $parameters['property'];
        // Use the createPdf method to create the desired type of PDF
        $options['className'] = 'FinquesFarnos\AppBundle\PdfGenerator\CustomTcpdf';
        $pdf = $this->createPdf('tcpdf', $options);
        // Call any native methods on the underlying library object
        /** @var \TCPDF $builder */
        $builder = $pdf->getNativeObject();
        $builder->SetAuthor('Finques Farnós, S.L.');
        $builder->SetTitle('Immoble '.$property->getReference());
        $builder->SetSubject('Immoble en PDF');
        $builder->SetKeywords('PDF, immoble');
        $builder->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $builder->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $builder->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $builder->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $builder->SetMargins(15, 30, 195); // (left, top, right)
        $builder->SetHeaderMargin(PDF_MARGIN_HEADER);
        $builder->SetFooterMargin(PDF_MARGIN_FOOTER);
        $builder->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
        $builder->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $builder->setFontSubsetting(true);
        $builder->setJPEGQuality(85); // set JPEG quality
        $builder->addPage();

        // HEADER
        $builder->Header();

        // BODY
        $builder->SetTextColor(107, 107, 107);
        $builder->SetFont('dejavusans', 'B', 9, '', true);
        $builder->Text(35, 52, 'Fitxa immoble ref. '.$property.' per a imprimir a l\'aparador');
        $builder->Text(35, 32, '(en desenvolupament)');

        // FOOTER
        $builder->Footer();

        // Return the original PDF, calling getContents to retrieve the rendered content
        return $pdf;
    }

    /**
     * Configure the parameters OptionsResolver.
     * Use this method to specify default and required options
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
                'property',
            ));
        $resolver->setAllowedTypes(array(
                'property' => 'FinquesFarnos\AppBundle\Entity\Property',
            ));
    }
}
