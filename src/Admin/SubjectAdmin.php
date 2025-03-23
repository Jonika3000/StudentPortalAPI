<?php

namespace App\Admin;

use App\Entity\Subject;
use App\Helper\FileHelper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SubjectAdmin extends AbstractAdmin
{
    public function __construct(private readonly FileHelper $fileHelper)
    {
        parent::__construct();
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('imagePath', FileType::class, [
                'required' => false,
                'mapped' => false,
            ]);
    }

    public function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Subject Details', ['class' => 'col-md-6'])
            ->add('id', null, [
                'label' => 'ID',
            ])
            ->add('name', null, [
                'label' => 'Name',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('imagePath', null, [
                'template' => 'admin/image_preview.html.twig',
                'label' => 'Image',
                'path' => $this->getSubjectImagePath(),
            ])
            ->end()
            ->with('Lessons Associated', ['class' => 'col-md-6'])
            ->add('lessons', null, [
                'label' => 'Lessons',
                'associated_property' => 'name',
            ])
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('name')
            ->add('description')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    protected function prePersist(object $object): void
    {
        $this->manageFileUpload($object);
    }

    protected function preUpdate(object $object): void
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload(Subject $subject): void
    {
        /** @var UploadedFile|null $file */
        $file = $this->getForm()->get('imagePath')->getData();

        if ($file instanceof UploadedFile) {
            $path = $this->fileHelper->uploadFile($file, '/images/subject/', false);
            $subject->setImagePath($path);
        }
    }

    private function getSubjectImagePath()
    {
        return $this->getSubject()->getImagePath();
    }
}
