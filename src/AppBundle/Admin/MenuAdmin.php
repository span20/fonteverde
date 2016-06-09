<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MenuAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text');
        $formMapper->add('item', 'entity', array('required' => false, 'class' => 'AppBundle\Entity\Content', 'empty_data' => '', 'empty_value' => 'Főoldal'));
        $formMapper->add('parent', 'entity', array('required' => false, 'class' => 'AppBundle\Entity\Menu', 'empty_data' => '', 'empty_value' => '--'));
        $formMapper->add('sort_order', 'number');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('parent');
        $listMapper->addIdentifier('sort_order');
        $listMapper->addIdentifier('createdAt');
        $listMapper->addIdentifier('updatedAt');
    }

    public function prePersist($data) {
        $data->setCreatedAt(new \DateTime());
        $data->setUpdatedAt(new \DateTime());
    }

    public function preUpdate($data) {
        $data->setUpdatedAt(new \DateTime());
    }
}
?>