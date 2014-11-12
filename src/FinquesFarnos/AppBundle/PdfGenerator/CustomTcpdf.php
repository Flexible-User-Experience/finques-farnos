<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

/**
 * Class CustomTcpdf
 *
 * @category PdfGenerator
 * @package  FinquesFarnos\AppBundle\PdfGenerator
 * @author   David Romaní <david@flux.cat>
 */
class CustomTcpdf extends \TCPDF {

    /**
     * Page header
     */
    public function Header() {
        // Set font
        $this->SetFont('dejavusans', 'B', 20);
        $this->SetTextColor(0, 137, 0);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    /**
     * Page footer
     */
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
