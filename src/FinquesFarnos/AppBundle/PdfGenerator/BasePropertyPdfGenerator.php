<?php

namespace FinquesFarnos\AppBundle\PdfGenerator;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Orkestra\Bundle\PdfBundle\Factory\FactoryRegistryInterface;
use Orkestra\Bundle\PdfBundle\Generator\AbstractPdfGenerator;
use Symfony\Component\Templating\EngineInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * BasePropertyPdfGenerator class.
 *
 * @category PdfGenerator
 *
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
     * @var AssetsHelper
     */
    protected $thas;

    /**
     * @var string kernel root dir
     */
    protected $krd;

    /**
     * Methods.
     */

    /**
     * BasePropertyPdfGenerator constructor.
     *
     * @param FactoryRegistryInterface $factoryRegistry
     * @param EngineInterface          $templatingEngine
     * @param RouterInterface          $router
     * @param Translator               $translator
     * @param CacheManager             $cm
     * @param UploaderHelper           $uh
     * @param AssetsHelper             $thas
     * @param string                   $krd
     */
    public function __construct(FactoryRegistryInterface $factoryRegistry, EngineInterface $templatingEngine, RouterInterface $router, Translator $translator, CacheManager $cm, UploaderHelper $uh, AssetsHelper $thas, $krd)
    {
        parent::__construct($factoryRegistry, $templatingEngine);
        $this->router = $router;
        $this->translator = $translator;
        $this->cm = $cm;
        $this->uh = $uh;
        $this->thas = $thas;
        $this->krd = $krd;
    }

    /**
     * @param \TCPDF $builder
     */
    protected function setBodyTextAndColor($builder)
    {
        $builder->SetFont('helvetica', '', 12, '', true);
        $this->setBodyTextGreyColor($builder);
    }

    /**
     * @param \TCPDF $builder
     * @param int    $y
     */
    protected function drawBrandLine($builder, $y)
    {
        $builder->Line($builder->getMargins()['left'], $y, $builder->getMargins()['right'], $y, CustomTcpdf::brandLineStyle());
    }

    /**
     * @param \TCPDF $builder
     */
    protected function setOrangeColor($builder)
    {
        $builder->SetTextColor(216, 111, 36);
    }

    /**
     * @param \TCPDF $builder
     */
    protected function setGreyColor($builder)
    {
        $builder->SetTextColor(101, 91, 69);
    }

    /**
     * @param \TCPDF $builder
     */
    protected function setBlackColor($builder)
    {
        $builder->SetTextColor(0, 0, 0);
    }

    /**
     * @param \TCPDF $builder
     */
    protected function setBodyTextGreyColor($builder)
    {
        $builder->SetTextColor(100, 100, 100);
    }

    /**
     * Get translation helper.
     *
     * @param string $msg
     *
     * @return string
     */
    protected function getTrans($msg)
    {
        return $this->translator->trans($msg, array(), 'messages');
    }
}
