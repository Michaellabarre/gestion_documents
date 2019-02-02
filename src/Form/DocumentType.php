<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isPublic', CheckboxType::class, [
            'label'    => 'Est public ?',
            'required' => false
        ]);
        $builder->add('allowedUsers', null, [
            'label' => 'Utilisateur(s) autorisé(s)'
        ]);

        if ($options['mode'] === 'create') {
            $builder->add('file', FileType::class, ['label' => 'Fichier']);
        } else {
            $builder->add('name', TextType::class, ['label' => 'Nom', 'disabled' => true]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'mode'       => 'create'
        ]);
    }
}
