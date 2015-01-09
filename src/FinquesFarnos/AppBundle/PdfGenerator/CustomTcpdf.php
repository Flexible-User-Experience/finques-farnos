<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

/**
 * Class CustomTcpdf
 *
 * @category PdfGenerator
 * @package  FinquesFarnos\AppBundle\PdfGenerator
 * @author   David Romaní <david@flux.cat>
 */
class CustomTcpdf extends \TCPDF
{
    /**
     * Page header
     */
    public function Header()
    {
        $this->drawBrandLine(23);
        $this->Image(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo-ff.png', $this->getMargins()['left'], 5, 30, 0, 'PNG', 'http://www.finquesfarnos.com', '', false, 150);
    }

    /**
     * Page footer
     */
    public function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('helvetica', '', 9);
        $this->setGreyColor();
        $this->drawBrandLine($this->GetY());
        $this->setCellPaddings(0, 0, 0, 0);
        $this->Cell(0, 15, 'C. Corsini 61 · 43870 Amposta · Tarragona · 977 702 721 · info@finquesfarnos.com · www.finquesfarnos.com', 0, false, 'L', 0, '', 0, false, 'T', 'C');
    }

    public function setOrangeColor()
    {
        $this->SetTextColor(216, 111, 36);
    }

    public function setGreyColor()
    {
        $this->SetTextColor(101, 91, 69);
    }

    public function setBodyTextGreyColor()
    {
        $this->SetTextColor(100, 100, 100);
    }

    public static function brandLineStyle()
    {
        return array(
            'width' => 1.5,
            'cap' => 'square',
            'join' => 'miter',
            'color' => array(216, 111, 36),
        );
    }

    public function drawBrandLine($y)
    {
        $this->Line($this->getMargins()['left'], $y, $this->getMargins()['right'], $y, $this->brandLineStyle());
    }
}
