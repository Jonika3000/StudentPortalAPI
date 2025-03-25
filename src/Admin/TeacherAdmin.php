<?php

namespace App\Admin;

use App\Constants\UserRoles;
use App\Entity\Teacher;
use App\Support\Helper\FileHelper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TeacherAdmin extends AbstractAdmin
{
    public function __construct(private readonly FileHelper $fileHelper, private readonly UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    public function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('User data', ['class' => 'col-md-6'])
            ->add('associatedUser', AdminType::class, [
                'required' => true,
                'label' => 'Associated User',
            ])
            ->end()
            ->with('Lessons', ['class' => 'col-md-6'])
            ->add('lesson', ModelType::class, [
                'multiple' => true,
                'required' => false,
                'label' => 'Lessons',
            ])
            ->end();
    }

    public function configureListFields(ListMapper $list): void
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
                    'edit' => [],
                    'show' => [],
                ],
                'label' => 'Actions',
            ]);
    }

    public function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Teacher Details')
            ->add('associatedUser.avatarPath', null, [
                'template' => 'admin/image_preview.html.twig',
                'label' => 'Avatar',
                'path' => $this->getSubjectAssociatedUserAvatarPath(),
            ])
            ->add('id', null, [
                'label' => 'ID',
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
            ->with('Homework Assigned')
            ->add('homework', null, [
                'label' => 'Homework',
                'associated_property' => 'title',
            ])
            ->end()
            ->with('Lessons Taught')
            ->add('lesson', null, [
                'label' => 'Lessons',
                'associated_property' => 'name',
            ])
            ->end();
    }

    protected function prePersist(object $object): void
    {
        $this->manageFileUpload($object);
        $this->addTeacherRole($object);
        $this->hashPassword($object);
    }

    protected function hashPassword(Teacher $teacher): void
    {
        $user = $teacher->getAssociatedUser();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
    }

    private function addTeacherRole(Teacher $teacher): void
    {
        $user = $teacher->getAssociatedUser();

        if ($user && !in_array(UserRoles::TEACHER, $user->getRoles(), true)) {
            $roles = $user->getRoles();
            $roles[] = UserRoles::TEACHER;
            $user->setRoles(array_unique($roles));
        }
    }

    protected function preUpdate(object $object): void
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload(Teacher $teacher): void
    {
        /** @var UploadedFile|null $file */
        $file = $this->getForm()->get('associatedUser')->get('avatarPath')->getData();

        if ($file instanceof UploadedFile) {
            $path = $this->fileHelper->uploadFile($file, '/images/avatars/', true);
            $teacher->getAssociatedUser()->setAvatarPath($path);
        }
    }

    public function getSubjectAssociatedUserAvatarPath(): ?string
    {
        $user = $this->getSubject()->getAssociatedUser();

        return $user ? $user->getAvatarPath() : null;
    }
}
