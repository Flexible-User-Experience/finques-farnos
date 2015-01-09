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
        $builder->setCellPaddings(0, 0, 0, 1);
        $this->drawBrandLine($builder, 130);
        $builder->SetX($builder->getMargins()['left'] - 2);
        $builder->SetY(135);
        $builder->SetFont('helvetica', '', 18, '', true);
        $this->setGreyColor($builder);
        $builder->MultiCell(115, 0, 'Ref. ' . $property->getReference(), 0, 'L', false, 1);
        $builder->SetFont('helvetica', 'B', 18, '', true);
        $builder->MultiCell(115, 0, $property->getName(), 0, 'L', false, 1);
        $this->setOrangeColor($builder);
        $builder->MultiCell(115, 0, $property->getDecoratedPrice(), 0, 'L', false, 1);
        $this->setBodyTextAndColor($builder);
        $builder->MultiCell(115, 5, '', 0, 'L', false, 1);
        $builder->MultiCell(115, 0, $property->getDescription(), 0, 'L', false, 1);

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

    protected function setBodyTextAndColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetFont('helvetica', '', 12, '', true);
        $this->setBodyTextGreyColor($builder);
    }

    protected function drawBrandLine($builder, $y)
    {
        /** @var \TCPDF $builder */
        $builder->Line($builder->getMargins()['left'], $y, $builder->getMargins()['right'], $y, CustomTcpdf::brandLineStyle());
    }

    protected function setOrangeColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(216, 111, 36);
    }

    protected function setGreyColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(101, 91, 69);
    }

    protected function setBodyTextGreyColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(100, 100, 100);
    }
}
