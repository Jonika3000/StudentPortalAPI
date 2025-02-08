<?php

namespace App\Admin;

use App\Entity\Student;
use App\Utils\FileHelper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class StudentAdmin extends AbstractAdmin
{
    public function __construct(private readonly FileHelper $fileHelper, private readonly UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('User data', ['class' => 'col-md-6'])
            ->add('associatedUser', AdminType::class, [
                'required' => false,
                'label' => 'Associated User',
            ])
            ->end()
            ->with('Student data', ['class' => 'col-md-6'])
            ->add('contactParent', TextType::class)
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('associatedUser.email', null, [
                'label' => 'Email',
            ])
            ->add('associatedUser.firstName', null, [
                'label' => 'First name',
            ])
            ->add('associatedUser.secondName', null, [
                'label' => 'Second name',
            ])
            ->add('associatedUser.birthday', null, [
                'label' => 'Birthday',
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ],
            ])
        ;
    }

    public function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Student Details')
            ->add('associatedUser.avatarPath', null, [
                'template' => 'admin/image_preview.html.twig',
                'label' => 'Avatar',
                'path' => $this->getSubjectAssociatedUserAvatarPath(),
            ])
            ->add('id', null, [
                'label' => 'ID',
            ])
            ->add('contactParent', null, [
                'label' => 'Contact parent',
            ])
            ->add('associatedUser.email', null, [
                'label' => 'Email',
            ])
            ->add('associatedUser.firstName', null, [
                'label' => 'First Name',
            ])
            ->add('associatedUser.secondName', null, [
                'label' => 'Second Name',
            ])
            ->add('associatedUser.birthday', null, [
                'label' => 'Birthday',
            ])
            ->add('associatedUser.address', null, [
                'label' => 'Address',
            ])
            ->add('associatedUser.phoneNumber', null, [
                'label' => 'Phone Number',
            ])
            ->add('associatedUser.gender', null, [
                'label' => 'Gender',
            ])
            ->end()
            ->with('Classroom')
            ->add('classRoom', null, [
                'label' => 'classroom',
                'associated_property' => 'uuid',
            ])
            ->end();
    }

    protected function prePersist(object $object): void
    {
        $this->manageFileUpload($object);
        $this->hashPassword($object);
    }

    protected function hashPassword(Student $student): void
    {
        $user = $student->getAssociatedUser();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
    }

    protected function preUpdate(object $object): void
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload(Student $student): void
    {
        /** @var UploadedFile|null $file */
        $file = $this->getForm()->get('associatedUser')->get('avatarPath')->getData();

        if ($file instanceof UploadedFile) {
            $path = $this->fileHelper->uploadFile($file, '/images/avatars/', true);
            $student->getAssociatedUser()->setAvatarPath($path);
        }
    }

    public function getSubjectAssociatedUserAvatarPath(): ?string
    {
        $user = $this->getSubject()->getAssociatedUser();

        return $user ? $user->getAvatarPath() : null;
    }
}
