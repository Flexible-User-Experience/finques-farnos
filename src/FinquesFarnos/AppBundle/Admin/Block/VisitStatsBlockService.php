<?php

namespace FinquesFarnos\AppBundle\Admin\Block;

use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * VisitStatsBlockService class.
 *
 * @category BlockService
 *
 * @author   David Romaní <david@flux.cat>
 */
class VisitStatsBlockService extends AbstractBlockService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * VisitStatsBlockService constructor.
     *
     * @param string          $type
     * @param EngineInterface $templating
     * @param EntityManager   $em
     */
    public function __construct($type, EngineInterface $templating, EntityManager $em)
    {
        parent::__construct($type, $templating);
        $this->em = $em;
    }

    /**
     * Execute.
     *
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $entities = $this->em->getRepository('AppBundle:Property')->getTop10VisitedArray();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block' => $blockContext->getBlock(),
                'settings' => $blockContext->getSettings(),
                'topVisitedProperties' => $entities,
            ),
            $response
        );
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return 'VisitStats';
    }

    /**
     * Set defaultSettings.
     *
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'title' => 'Default title',
                'content' => 'Default content',
                'template' => '::Admin/Block/block_visit_stats.html.twig',
            )
        );
    }
}
