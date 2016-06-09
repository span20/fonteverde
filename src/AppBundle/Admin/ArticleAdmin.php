<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text');
        $formMapper->add('lead', 'textarea');
        $formMapper->add('content', 'textarea');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
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