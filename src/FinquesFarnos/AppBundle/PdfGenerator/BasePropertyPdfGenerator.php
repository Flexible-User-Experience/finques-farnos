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
 * BasePropertyPdfGenerator class
 *
 * @category PdfGenerator
 * @package  FinquesFarnos\AppBundle\PdfGenerator
 * @author   David Romaní <david@flux.cat>
 */
abstract class BasePropertyPdfGenerator extends AbstractPdfGenerator
{
    /**
     * @var RouterInterface $router
     */
    protected $router;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var CacheManager $cm
     */
    protected $cm;

    /**
     * @var UploaderHelper $uh
     */
    protected $uh;

    /**
     * @var string $krd kernel root dir
     */
    protected $krd;

    public function __construct(FactoryRegistryInterface $factoryRegistry, EngineInterface $templatingEngine, RouterInterface $router, Translator $translator, CacheManager $cm, UploaderHelper $uh, $krd)
    {
        parent::__construct($factoryRegistry, $templatingEngine);
        $this->router = $router;
        $this->translator = $translator;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->krd = $krd;
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
    protected function getTrans($msg)
    {
        return $this->translator->trans(
            $msg,
            array(),
            'messages'
        );
    }
}
