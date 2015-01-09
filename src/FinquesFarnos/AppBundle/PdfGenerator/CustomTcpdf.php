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
        // Set font
        $this->SetFont('dejavusans', 'B', 20);
        $this->SetTextColor(0, 137, 0);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
        $this->SetX($this->getMargins()['left'] - 2);
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

    public function brandLineStyle()
    {
        return array(
            'width' => 2,
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
