<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use FinquesFarnos\AppBundle\Entity\ImageProperty;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FinquesFarnos\AppBundle\Entity\Property;
use Orkestra\Bundle\PdfBundle\Pdf\PdfInterface;

/**
 * PropertyWebPdfGenerator class.
 *
 * @category PdfGenerator
 *
 * @author   David Romaní <david@flux.cat>
 */
class PropertyWebPdfGenerator extends BasePropertyPdfGenerator
{
    /**
     * Performs the PDF generation.
     *
     * @param array $parameters An array of parameters to be used to render the PDF
     * @param array $options    An array of options to be passed to the underlying PdfFactory
     *
     * @return PdfInterface
     *
     * @throws \Exception
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
        $builder->SetTitle('Ref_'.$property->getReference().'_'.$property->getName());
        $builder->SetSubject('Immoble en PDF');
        $builder->SetKeywords('PDF, immoble');
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
        // --> images
        $row = 0;
        $col = 0;
        $items = 0;
        /** @var ImageProperty $image */
        foreach ($property->getImages() as $image) {
            if ($image->getEnabled() && $items < 6) {
                if ($col > 2) {
                    $col = 0;
                    ++$row;
                }
                $builder->Image(
                    $this->krd.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.$this->uh->asset($image, 'imageFile'),
                    $builder->getMargins()['left'] + $col * 62, // abscissa of the upper-left corner
                    35 + $row * 48,                             // ordinate of the upper-left corner
                    57,                                         // width
                    43,                                         // height
                    '',                                         // image file extension
                    $this->cm->generateUrl($this->uh->asset($image, 'imageFile'), '757x450') // link
                   );
                ++$col;
            }
            ++$items;
        }
        if ($row > 1) {
            $row = 1;
        }
        // --> left text
        $y = 38 + ($row + 1) * 48; //135;
        $builder->setCellPaddings(0, 0, 0, 1);
        $this->drawBrandLine($builder, $y);
        $builder->SetX($builder->getMargins()['left'] - 2);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', '', 12, '', true);
        $this->setBlackColor($builder);
        $builder->MultiCell(115, 0, 'Ref. '.$property->getReference(), 0, 'L', false, 1);
        $builder->SetFont('helvetica', 'B', 18, '', true);
        $this->setGreyColor($builder);
        $builder->MultiCell(115, 0, mb_strtoupper($property->getName(), 'UTF-8'), 0, 'L', false, 1);
        $builder->SetFont('helvetica', '', 15, '', true);
        $this->setOrangeColor($builder);
        if ($property->getHidePrice()) {
            $builder->MultiCell(115, 0, $this->getTrans('homepage.property.ask'), 0, 'L', false, 1);
        } else {
            if ($property->getShowPriceOnlyWithNumbers()) {
                if ($property->getOldPrice()) {
                    $builder->Text($builder->getMargins()['left'], $builder->getY(), $property->getDecoratedPrice(), false, false, true);
                    $xOffset = $builder->getMargins()['left'] + $builder->GetStringWidth($property->getDecoratedPrice()) + 2;
                    $builder->SetFont('helvetica', '', 10, '', true);
                    $this->setBlackColor($builder);
                    $builder->Text($xOffset, $builder->getY() + 1.75, $this->getTrans('homepage.property.before').' '.$property->getDecoratedOldPrice(), false, false, true, 0);
                    $builder->Ln();
                    $builder->setY($builder->getY() + 3);
                } else {
                    $builder->MultiCell(115, 0, $property->getDecoratedPrice(), 0, 'L', false, 1);
                }
            } else {
                $builder->MultiCell(115, 0, $this->getTrans('homepage.property.since').' '.$property->getDecoratedPrice(), 0, 'L', false, 1);
            }
        }
        // ribbons
        $frozenY = $y;
        $builder->SetY($builder->getY() + 5);
        $y = $builder->getY() + 5;
        $this->setBlackColor($builder);
        $builder->SetFont('helvetica', '', 9, '', true);
        $builder->setLineStyle(array('width' => 0.25, 'cap' => 'square', 'join' => 'miter', 'color' => array(0, 0, 0)));
        $builder->setCellPaddings(0, 0, 0, 0);
        $builder->MultiCell(115, 1, '', 0, 'L', false, 1);
        $x = $builder->getMargins()['left'] + 11;
        if ($property->getOfferSpecial()) {
            $builder->MultiCell(23, 15, $this->getTrans('homepage.property.offer.special'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'immobiliaria'.DIRECTORY_SEPARATOR.'color'.DIRECTORY_SEPARATOR.'oferta_color.png', $x - 4, $y);
            $x = $x + 24;
        }
        if ($property->getOfferDiscount() && !$property->getOfferSpecial()) {
            $builder->MultiCell(23, 15, $this->getTrans('homepage.property.offer.discount'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'immobiliaria'.DIRECTORY_SEPARATOR.'color'.DIRECTORY_SEPARATOR.'rebaixa_color.png', $x - 4, $y);
            $x = $x + 24;
        }
        if ($property->getSquareMeters()) {
            $builder->MultiCell(23, 15, $property->getSquareMeters().' m²', 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'immobiliaria'.DIRECTORY_SEPARATOR.'color'.DIRECTORY_SEPARATOR.'casa_color.png', $x - 5, $y);
            $x = $x + 24;
        }
        if ($property->getRooms()) {
            $builder->MultiCell(28, 15, $property->getRooms().' '.$this->getTrans('homepage.property.rooms'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'immobiliaria'.DIRECTORY_SEPARATOR.'color'.DIRECTORY_SEPARATOR.'dormitoris_color.png', $x - 3, $y);
            $x = $x + 29;
        }
        if ($property->getBathrooms()) {
            $builder->MultiCell(23, 15, $property->getBathrooms().' '.$this->getTrans('homepage.property.bathrooms'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'immobiliaria'.DIRECTORY_SEPARATOR.'color'.DIRECTORY_SEPARATOR.'banys_color.png', $x - 6, $y);
        }
        $builder->MultiCell(2, 15, '', 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B'); // last vertical separator line
        // description
        $this->setBodyTextAndColor($builder);
        $builder->MultiCell(33, 15, '', 0, 'C', 0, 1, '', '', true, 0, false, true, 17, 'B');
        $builder->MultiCell(115, 5, '', 0, 'L', false, 1);
        $builder->setCellPaddings(0, 0, 0, 1);
        $this->setBlackColor($builder);
        $builder->MultiCell(115, 0, $property->getDescription(), 0, 'L', false, 1);
        // --> right text
        $y = $frozenY;
        $builder->SetX(120);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', '', 15, '', true);
        $this->setBlackColor($builder);
        $builder->MultiCell(55, 0, mb_convert_case($this->getTrans('property.energy.efficiency'), MB_CASE_TITLE, 'UTF-8'), 0, 'L', false, 2, 140, $y + 5);
        $y = $y + 15;
        $builder->SetFont('helvetica', '', 10, '', true);
        if (0 == $property->getEnergyClass()) {
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'warning.png', 140, $builder->getY() + 2, 4, 4, 'PNG', '', '', false, 150);
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.noclass'), 0, 'L', false, 2, 145, $y);
        } elseif (1 == $property->getEnergyClass()) {
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'warning.png', 140, $builder->getY() + 2, 4, 4, 'PNG', '', '', false, 150);
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.pending'), 0, 'L', false, 2, 145, $y);
        } elseif ($property->getEnergyClass() > 1) {
            $builder->SetAlpha(2 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_A.png', 140, $y, 14, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(3 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_B.png', 140, $y + 8, 20, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(4 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_C.png', 140, $y + 16, 28, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(5 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_D.png', 140, $y + 24, 35, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(6 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_E.png', 140, $y + 32, 43, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(7 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_F.png', 140, $y + 40, 50, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(8 == $property->getEnergyClass() ? 1 : 0.35);
            $builder->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icones'.DIRECTORY_SEPARATOR.'eficiencia_energetica'.DIRECTORY_SEPARATOR.'EF_G.png', 140, $y + 48, 57, 5, 'PNG', '', '', false, 150);
            $builder->SetAlpha(1);
        }

        // FOOTER
        $url = $this->router->generate('front_property', array(
                'type' => $property->getType()->getNameSlug(),
                'city' => $property->getCity()->getNameSlug(),
                'name' => $property->getNameSlug(),
                'reference' => $property->getReference(),
            ), true);
        $printDate = date_format(new \DateTime('now'), 'd/m/Y');
        $builder->SetFont('helvetica', '', 9, '', true);
        $builder->Text($builder->getMargins()['left'], 269, $this->getTrans('pdf.print.date').': '.$printDate);
        $builder->Text($builder->getMargins()['left'], 274, $url);
        $builder->Footer();

        // Return the original PDF, calling getContents to retrieve the rendered content
        return $pdf;
    }

    /**
     * Configure the parameters OptionsResolver.
     * Use this method to specify default and required options.
     *
     * @param OptionsResolver $resolver
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('property'));
        $resolver->setAllowedTypes('property', array('property' => 'FinquesFarnos\AppBundle\Entity\Property'));
    }
}
