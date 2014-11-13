<?php

namespace FinquesFarnos\AppBundle\Admin\Block;

use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * VisitStatsBlockService class
 *
 * @category BlockService
 * @package  FinquesFarnos\AppBundle\Admin\Block
 * @author   David Romaní <david@flux.cat>
 */
class VisitStatsBlockService extends BaseBlockService
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($type, $templating, $em)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->em = $em;
    }

    /**
     * Execute
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
                'block'                => $blockContext->getBlock(),
                'settings'             => $blockContext->getSettings(),
                'topVisitedProperties' => $entities,
            ),
            $response
        );
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'VisitStats';
    }

    /**
     * Set defaultSettings
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'title'    => 'Default title',
                'content'  => 'Default content',
                'template' => '::Admin/Block/block_visit_stats.html.twig'
            )
        );
    }
}
