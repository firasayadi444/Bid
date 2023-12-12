<?php

namespace App\Form;

use App\Entity\Article;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('date_fin', DateTimeImmutableType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss', // adjust the format as needed
                'html5' => false, // prevent HTML5 validation, as Symfony might not fully support DateTimeImmutable with HTML5
                'attr' => ['class' => 'datetimepicker'], // add any additional attributes or classes
            ])
            ->add('prix_depart')
            ->add('prix_final')
             // Add the file upload field
             ->add('imageFile', VichImageType::class, [
                'required' => false, // Set to true if the image is mandatory
                'allow_delete' => true,
                'download_uri' => false,
            ]);

        // Add the existing image field (if you want to display it in the form)
        $builder->add('image', HiddenType::class, [
            'mapped' => false,
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
