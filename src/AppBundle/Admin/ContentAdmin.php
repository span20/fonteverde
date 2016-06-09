<?php
namespace AppBundle\Admin;

use Cocur\Slugify\Slugify;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContentAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $image = $this->getSubject();

        $fileFieldOptions = array('required' => false);
        if ($image && ($webPath = $image->getFileFullPath())) {
            // get the container so the full path to the image can be set
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath().'/'.$webPath;

            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" style="max-height: 200px; max-width: 200px;" />';
        }

        $formMapper->add('title', 'text');

        $formMapper->add('file', 'file', $fileFieldOptions);

        $formMapper->add('main_content', 'textarea', array('attr' => array('class' => 'ckeditor')));
        $formMapper->add('content_1', 'textarea', array('attr' => array('class' => 'ckeditor')));
        $formMapper->add('content_2', 'textarea', array('attr' => array('class' => 'ckeditor')));
        $formMapper->add('content_3', 'textarea', array('attr' => array('class' => 'ckeditor')));
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

        $this->manageFileUpload($data);

        $slugger = new Slugify();

        $slugger->addRule('ö', 'o');
        $slugger->addRule('Ö', 'o');
        $slugger->addRule('ő', 'o');
        $slugger->addRule('Ő', 'o');
        $slugger->addRule('ü', 'u');
        $slugger->addRule('Ü', 'u');
        $slugger->addRule('ű', 'u');
        $slugger->addRule('Ű', 'u');

        $data->setSlug($slugger->slugify($data->getTitle()));
    }

    public function preUpdate($data) {
        $data->setUpdatedAt(new \DateTime());

        $this->manageFileUpload($data);

        $slugger = new Slugify();

        $slugger->addRule('ö', 'o');
        $slugger->addRule('Ö', 'o');
        $slugger->addRule('ő', 'o');
        $slugger->addRule('Ő', 'o');
        $slugger->addRule('ü', 'u');
        $slugger->addRule('Ü', 'u');
        $slugger->addRule('ű', 'u');
        $slugger->addRule('Ű', 'u');

        $data->setSlug($slugger->slugify($data->getTitle()));
    }

    private function manageFileUpload($image)
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }
}
?>