<?php

namespace FinquesFarnos\AppBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FinquesFarnos\AppBundle\Entity\ContactMessage;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Routing\Router;

/**
 * ContactAdmin class
 *
 * @category Admin
 * @package  FinquesFarnos\AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ContactAdmin extends BaseAdmin
{
    /**
     * Base admin route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'contact';

    /**
     * Form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contacte', array('class' => 'col-md-6'))
            ->add('name', null, array('label' => 'Nom'))
            ->add('phone', null, array('label' => 'Telèfon'))
            ->add('email', null, array(
                'label' => 'Email',
                'read_only' => $this->id($this->getSubject()),
                ))
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add('enabled', 'checkbox', array('label' => 'Actiu', 'required' => false))
            ->end()
            ->with('Missatges', array('class' => 'col-md-12'))
            ->add('messages2', 'text', array(
                    'required' => false,
                    'mapped' => false,
                    'label' => 'Missatges',
                    'read_only' => true,
                    'label_render' => false,
                    'help' => $this->getMessagesHelperFormMapper(),
                ))
            ->end();
    }

    /**
     * List view
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('label' => 'Nom', 'editable' => true))
            ->add('phone', null, array('label' => 'Telèfon', 'editable' => true))
            ->add('email', null, array('label' => 'Email', 'editable' => false))
            ->add('messages', 'integer', array('label' => 'Missatges', 'template' => '::Admin/contact_messages_count_list_field.html.twig'))
            ->add('date', 'date', array('label' => 'Últim missatge', 'template' => '::Admin/contact_messages_date_list_field.html.twig'))
            ->add('enabled', null, array('label' => 'Actiu', 'editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                    ),
                    'label' => 'Accions',
                ));
    }

    /**
     * List filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('phone', null, array('label' => 'Telèfon'))
            ->add('email', null, array('label' => 'Email'))
            ->add('enabled', null, array('label' => 'Actiu'));
    }

    /**
     * Get messages helper form mapper
     *
     * @return string
     */
    private function getMessagesHelperFormMapper()
    {
        /** @var Router $rs */
        $rs = $this->getConfigurationPool()->getContainer()->get('router');
        /** @var ArrayCollection $messages */
        $messages = $this->getSubject()->getMessages();
        if ($messages->count() > 0) {
            $result = '<table class="table table-hover"><thead><tr><th>Data</th><th>Immoble</th><th>Text</th></tr></thead><tbody>';
            /** @var ContactMessage $message */
            foreach ($messages as $message) {
                if ($message->getProperty()) {
                    $result .= '<tr><td>'.$message->getCreatedAt()->format('d/m/Y H:i:s').'</td><td><a href="'.$rs->generate('admin_finquesfarnos_app_property_edit', array('id' => $message->getProperty()->getId())).'">'.$message->getProperty()->getReference().'</a></td><td>'.$message->getText().'</td></tr>';
                } else {
                    $result .= '<tr><td>'.$message->getCreatedAt()->format('d/m/Y H:i:s').'</td><td>---</td><td>'.$message->getText().'</td></tr>';
                }

            }

            return $result.'</tbody></table>';
        }

        return '';
    }
}
