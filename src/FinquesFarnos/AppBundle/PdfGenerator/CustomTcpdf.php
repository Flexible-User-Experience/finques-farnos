<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

/**
 * Class CustomTcpdf.
 *
 * @category PdfGenerator
 *
 * @author   David Romaní <david@flux.cat>
 */
class CustomTcpdf extends \TCPDF
{
    /**
     * Page header.
     */
    public function Header()
    {
        $this->drawBrandLine(28);
        $this->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'logo-ff.png', $this->getMargins()['left'], 5, 45, 0, 'PNG', 'http://www.finquesfarnos.com', '', false, 150);
        $this->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'logo-pdf-api.jpg', $this->getMargins()['left'] + 122, 13, 15, 0);
        $this->Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'logo-pdf-rm.jpg', $this->getMargins()['left'] + 141, 13, 40, 0);
    }

    /**
     * Page footer.
     */
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 9);
        $this->setBlackColor();
        $this->drawBrandLine($this->GetY());
        $this->setCellPaddings(0, 0, 0, 0);
        $this->Cell($this->getMargins()['right'] - $this->getMargins()['left'], 10, 'C. Corsini 61 · 43870 Amposta · Tarragona · 977 702 721 · info@finquesfarnos.com · www.finquesfarnos.com', 0, false, 'C', 0, '', 0, false, 'T', 'C');
    }

    /**
     * Set color.
     */
    public function setOrangeColor()
    {
        $this->SetTextColor(216, 111, 36);
    }

    /**
     * Set color.
     */
    public function setGreyColor()
    {
        $this->SetTextColor(101, 91, 69);
    }

    /**
     * Set color.
     */
    public function setBodyTextGreyColor()
    {
        $this->SetTextColor(100, 100, 100);
    }

    /**
     * Set color.
     */
    public function setBlackColor()
    {
        $this->SetTextColor(0, 0, 0);
    }

    /**
     * @return array
     */
    public static function brandLineStyle()
    {
        return array(
            'width' => 0.5,
            'cap' => 'square',
            'join' => 'miter',
            'color' => array(216, 111, 36),
        );
    }

    /**
     * @param int $y
     */
    public function drawBrandLine($y)
    {
        $this->Line($this->getMargins()['left'], $y, $this->getMargins()['right'], $y, $this->brandLineStyle());
    }
}
