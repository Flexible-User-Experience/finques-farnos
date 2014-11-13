<?php

namespace FinquesFarnos\AppBundle\Admin\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
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
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), array(
                'block'     => $blockContext->getBlock(),
                'settings'  => $blockContext->getSettings()
            ), $response);
    }

//    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
//    {
//    }

//    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
//    {
//        $formMapper->add('settings', 'sonata_type_immutable_array', array(
//                'keys' => array(
//                    array('content', 'textarea', array()),
//                )
//            ));
//    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'Visit Stats';
    }

    /**
     * Set defaultSettings
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'title'  => 'replace this title',
                'content'  => 'replace this content',
                'template' => '::Admin/Block/block_visit_stats.html.twig'
            ));
    }
}
