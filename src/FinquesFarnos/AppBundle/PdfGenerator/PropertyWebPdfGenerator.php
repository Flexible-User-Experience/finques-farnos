<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use FinquesFarnos\AppBundle\Entity\ImageProperty;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Orkestra\Bundle\PdfBundle\Factory\FactoryRegistryInterface;
use Orkestra\Bundle\PdfBundle\Generator\AbstractPdfGenerator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FinquesFarnos\AppBundle\Entity\Property;
use Symfony\Component\Templating\EngineInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\RouterInterface;

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
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var CacheManager $cm
     */
    private $cm;

    /**
     * @var UploaderHelper $uh
     */
    private $uh;

    /**
     * @var string $krd kernel root dir
     */
    private $krd;

    public function __construct(FactoryRegistryInterface $factoryRegistry, EngineInterface $templatingEngine, RouterInterface $router, Translator $translator, CacheManager $cm, UploaderHelper $uh, $krd)
    {
        parent::__construct($factoryRegistry, $templatingEngine);
        $this->router = $router;
        $this->translator = $translator;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->krd = $krd;
    }

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
        $builder->SetTitle('Ref_' . $property->getReference() . '_' . $property->getName());
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
                    $row++;
                }
                $builder->Image(
                    $this->krd . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . $this->uh->asset($image, 'imageFile'),
                    $builder->getMargins()['left'] + $col * 62, // abscissa of the upper-left corner
                    30 + $row * 48,                             // ordinate of the upper-left corner
                    57,                                         // width
                    43,                                         // height
                    '',                                         // image file extension
                    $this->cm->generateUrl($this->uh->asset($image, 'imageFile'), '757x450') // link
                   );
                $col++;
            }
            $items++;
        }
        if ($row > 1) {
            $row = 1;
        }
        // --> left text
        $y = 33 + ($row + 1) * 48; //135;
        $builder->setCellPaddings(0, 0, 0, 1);
        $this->drawBrandLine($builder, $y);
        $builder->SetX($builder->getMargins()['left'] - 2);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', '', 18, '', true);
        $this->setGreyColor($builder);
        $builder->MultiCell(115, 0, 'Ref. ' . $property->getReference(), 0, 'L', false, 1);
        $builder->SetFont('helvetica', 'B', 18, '', true);
        $builder->MultiCell(115, 0, mb_strtoupper($property->getName()), 0, 'L', false, 1);
        $this->setOrangeColor($builder);
        if ($property->getShowPriceOnlyWithNumbers()) {
            if ($property->getOldPrice()) {
                $builder->MultiCell(115, 0, $property->getDecoratedPrice() . ' ' . $this->getTrans('homepage.property.before') . ' ' . $property->getDecoratedOldPrice(), 0, 'L', false, 1);
            } else {
                $builder->MultiCell(115, 0, $property->getDecoratedPrice(), 0, 'L', false, 1);
            }
        } else {
            $builder->MultiCell(115, 0, $this->getTrans('homepage.property.since') . ' ' . $property->getDecoratedPrice(), 0, 'L', false, 1);
        }
        // ribbons
        $this->setGreyColor($builder);
        $builder->SetFont('helvetica', '', 9, '', true);
        $builder->setLineStyle(array('width' => 0.25, 'cap' => 'square', 'join' => 'miter', 'color' => array(100, 100, 100)));
        $builder->setCellPaddings(0, 0, 0, 0);
        $builder->MultiCell(115, 1, '', 0, 'L', false, 1);
        if ($property->getSquareMeters()) {
            $builder->MultiCell(33, 15, $property->getSquareMeters() . ' m²', 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'immobiliaria' . DIRECTORY_SEPARATOR . 'color' . DIRECTORY_SEPARATOR . 'casa_color.png', 26, $y + 37);
        }
        if ($property->getRooms()) {
            $builder->MultiCell(33, 15, $property->getRooms() . ' ' . $this->getTrans('homepage.property.rooms'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'immobiliaria' . DIRECTORY_SEPARATOR . 'color' . DIRECTORY_SEPARATOR . 'dormitoris_color.png', 60, $y + 37);
        }
        if ($property->getBathrooms()) {
            $builder->MultiCell(33, 15, $property->getBathrooms() . ' ' . $this->getTrans('homepage.property.bathrooms'), 'L', 'C', 0, 0, '', '', true, 0, false, true, 17, 'B');
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'immobiliaria' . DIRECTORY_SEPARATOR . 'color' . DIRECTORY_SEPARATOR . 'banys_color.png', 93, $y + 37);
        }
        // description
        $this->setBodyTextAndColor($builder);
        $builder->MultiCell(33, 15, '', 0, 'C', 0, 1, '', '', true, 0, false, true, 17, 'B');
        $builder->MultiCell(115, 5, '', 0, 'L', false, 1);
        $builder->setCellPaddings(0, 0, 0, 1);
        $builder->MultiCell(115, 0, $property->getDescription(), 0, 'L', false, 1);
        // --> right text
        $builder->SetX(120);
        $builder->SetY($y + 5);
        $builder->SetFont('helvetica', 'B', 18, '', true);
        $this->setGreyColor($builder);
        $builder->MultiCell(55, 0, $this->getTrans('property.energy.efficiency'), 0, 'L', false, 2, 140, $y + 5);
        $builder->SetFont('helvetica', '', 12, '', true);
        if ($property->getEnergyClass() == 0) {
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.noclass'), 0, 'L', false, 2, 140, $y + 23);
        } else if ($property->getEnergyClass() == 1) {
            $builder->MultiCell(55, 0, $this->getTrans('property.energy.pending'), 0, 'L', false, 2, 140, $y + 23);
        } else if ($property->getEnergyClass() > 1) {
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_A' . ($property->getEnergyClass() == 2 ? '' : '_01') . '.png', 140, $y + 25, 14, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_B' . ($property->getEnergyClass() == 3 ? '' : '_01') . '.png', 140, $y + 33, 20, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_C' . ($property->getEnergyClass() == 4 ? '' : '_01') . '.png', 140, $y + 41, 28, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_D' . ($property->getEnergyClass() == 5 ? '' : '_01') . '.png', 140, $y + 49, 35, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_E' . ($property->getEnergyClass() == 6 ? '' : '_01') . '.png', 140, $y + 57, 43, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_F' . ($property->getEnergyClass() == 7 ? '' : '_01') . '.png', 140, $y + 65, 50, 5, 'PNG', '', '', false, 150);
            $builder->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'icones' . DIRECTORY_SEPARATOR . 'eficiencia_energetica' . DIRECTORY_SEPARATOR . 'EF_G' . ($property->getEnergyClass() == 8 ? '' : '_01') . '.png', 140, $y + 73, 57, 5, 'PNG', '', '', false, 150);
        }

        // FOOTER
        $url = $this->router->generate('front_property', array(
                'type' => $property->getType()->getNameSlug(),
                'city' => $property->getCity()->getNameSlug(),
                'name' => $property->getNameSlug(),
                'reference' => $property->getReference(),
            ), true);
        $builder->SetFont('helvetica', '', 9, '', true);
        $builder->Text($builder->getMargins()['left'], 267, $url);
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

    /**
     * Get translation helper
     *
     * @param $msg
     *
     * @return string
     */
    private function getTrans($msg)
    {
        return $this->translator->trans(
            $msg,
            array(),
            'messages'
        );
    }
}
