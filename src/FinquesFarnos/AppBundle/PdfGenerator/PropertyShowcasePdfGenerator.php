<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\ImageProperty;

/**
 * PropertyShowcasePdfGenerator class
 *
 * @category PdfGenerator
 * @package  FinquesFarnos\AppBundle\PdfGenerator
 * @author   David Romaní <david@flux.cat>
 */
class PropertyShowcasePdfGenerator extends BasePropertyPdfGenerator
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
        $options['format'] = 'A4';
        $options['encoding'] = 'UTF-8';
        $options['unicode'] = true;
        $pdf = $this->createPdf('tcpdf', $options);
        // Call any native methods on the underlying library object
        /** @var \TCPDF $builder */
        $builder = $pdf->getNativeObject();
        $builder->SetAuthor('Finques Farnós, S.L.');
        $builder->SetTitle('Ref_' . $property->getReference() . '_' . $property->getName());
        $builder->SetSubject('Immoble en PDF aparador');
        $builder->SetKeywords('PDF, immoble, aparador');
        $builder->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $builder->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $builder->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $builder->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $builder->SetMargins(15, 30, 196); // (left, top, right)
        $builder->SetHeaderMargin(PDF_MARGIN_HEADER);
        $builder->SetFooterMargin(PDF_MARGIN_FOOTER);
        $builder->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
        $builder->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $builder->setFontSubsetting(true);
        $builder->setJPEGQuality(85); // set JPEG quality
        $builder->addPage('P', 'A4');

        // HEADER
        $builder->Header();

        // BODY
        // --> left text
        $y = 172;
        $builder->setCellPaddings(0, 0, 0, 1);
        $this->drawBrandLine($builder, $y);
        $builder->SetX($builder->getMargins()['left'] - 2);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', '', 15, '', true);
        $this->setBlackColor($builder);
        $builder->MultiCell(135, 0, 'Ref. ' . $property->getReference(), 0, 'L', false, 1);
        $builder->SetFont('helvetica', 'B', 30, '', true);
        $this->setGreyColor($builder);
        $builder->MultiCell(135, 0, mb_strtoupper($property->getName(), 'UTF-8'), 0, 'L', false, 1);
        $this->setOrangeColor($builder);
        if ($property->getShowPriceOnlyWithNumbers()) {
            if ($property->getOldPrice()) {
                $builder->MultiCell(135, 0, $property->getDecoratedPrice() . ' ' . $this->getTrans('homepage.property.before') . ' ' . $property->getDecoratedOldPrice(), 0, 'L', false, 1);
            } else {
                $builder->MultiCell(135, 0, $property->getDecoratedPrice(), 0, 'L', false, 1);
            }
        } else {
            $builder->MultiCell(135, 0, $this->getTrans('homepage.property.since') . ' ' . $property->getDecoratedPrice(), 0, 'L', false, 1);
        }
        // description
        $this->setBlackColor($builder);
        $builder->SetFont('helvetica', '', 18, '', true);
        $builder->setCellPaddings(0, 0, 0, 1);
        $builder->MultiCell(135, 55, $property->getDescription(), 0, 'J', false, 1, null, null, true, 1, false, false, 60, 'T', true);

        // Energy efficency
        $builder->setCellPaddings(0, 0, 0, 2);
        $transCode = array(2 => 'A', 3 => 'B', 4 => 'C', 5 => 'D', 6 => 'E', 7 => 'F', 8 => 'G');
        $builder->SetX(155);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', '', 11, '', true);
        $this->setBlackColor($builder);
        $builder->MultiCell(55, 0, mb_convert_case($this->getTrans('property.energy.efficiency'), MB_CASE_TITLE, 'UTF-8'), 0, 'L', false, 2, 155);
        if ($property->getEnergyClass() == 0) {
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.noclass'), 0, 'L', false, 2, 155);
        } else if ($property->getEnergyClass() == 1) {
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.pending'), 0, 'L', false, 2, 155);
        } else if ($property->getEnergyClass() > 1) {
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_' . $transCode[$property->getEnergyClass()] . '.png', 155, $builder->getY(), 14 + (4.5 * ($property->getEnergyClass() - 2)), 6, 'PNG', '', '', false, 150);
            $builder->setY($builder->getY() + 7);
        }

        // QR Code
        $url = $this->router->generate('front_property', array(
            'type' => $property->getType()->getNameSlug(),
            'city' => $property->getCity()->getNameSlug(),
            'name' => $property->getNameSlug(),
            'reference' => $property->getReference(),
        ), true);
        $style = array(
            'border' => false,
            'vpadding' => 0,
            'hpadding' => 0,
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false,  // array(255, 255, 255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $builder->write2DBarcode($url, 'QRCODE,M', 155, $builder->getY() + 10, 41, 41, $style, 'N', true);

        // FOOTER
        $builder->SetFont('helvetica', '', 9, '', true);
        //$builder->Text($builder->getMargins()['left'], 267, $url);
        $builder->Footer();

        // Help dashed line
        $builder->Line($builder->getMargins()['left'] + 5, 36, $builder->getMargins()['right'] - 5, 36, array(
                'width' => 0.2,
                'cap' => 'square',
                'join' => 'miter',
                'dash' => '5,10',
                'color' => array(200, 200, 200),
            ));

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
