<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Orkestra\Bundle\PdfBundle\Factory\FactoryRegistryInterface;
use Orkestra\Bundle\PdfBundle\Generator\AbstractPdfGenerator;
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
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var CacheManager
     */
    protected $cm;

    /**
     * @var UploaderHelper
     */
    protected $uh;

    /**
     * @var string $krd kernel root dir
     */
    protected $krd;

    /**
     * Methods
     */

    /**
     * BasePropertyPdfGenerator constructor.
     *
     * @param FactoryRegistryInterface $factoryRegistry
     * @param EngineInterface $templatingEngine
     * @param RouterInterface $router
     * @param Translator $translator
     * @param CacheManager $cm
     * @param UploaderHelper $uh
     * @param string $krd
     */
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
     * @param $builder
     */
    protected function setBodyTextAndColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetFont('helvetica', '', 12, '', true);
        $this->setBodyTextGreyColor($builder);
    }

    /**
     * @param $builder
     * @param $y
     */
    protected function drawBrandLine($builder, $y)
    {
        /** @var \TCPDF $builder */
        $builder->Line($builder->getMargins()['left'], $y, $builder->getMargins()['right'], $y, CustomTcpdf::brandLineStyle());
    }

    /**
     * @param $builder
     */
    protected function setOrangeColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(216, 111, 36);
    }

    /**
     * @param $builder
     */
    protected function setGreyColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(101, 91, 69);
    }

    /**
     * @param $builder
     */
    protected function setBlackColor($builder)
    {
        /** @var \TCPDF $builder */
        $builder->SetTextColor(0, 0, 0);
    }

    /**
     * @param $builder
     */
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
        return $this->translator->trans($msg, array(), 'messages');
    }
}
