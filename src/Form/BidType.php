<?php

namespace App\Form;

use App\Entity\Bid;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class BidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ... other form fields

            ->add('bidingprice', TextType::class, [
                'label' => 'Bid Price',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => $options['min_price'],
                        'message' => 'The bid price must be greater than or equal to {{ compared_value }}.',
                    ]),
                    new LessThanOrEqual([
                        'value' => $options['max_price'],
                        'message' => 'The bid price must be less than or equal to {{ compared_value }}.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bid::class,
        ]);

        $resolver->setRequired(['article']); // Make sure 'article' option is required
        $resolver->setAllowedTypes('article', 'App\Entity\Article');

        $resolver->setDefault('min_price', null);
        $resolver->setDefault('max_price', null);

        $resolver->setNormalizer('min_price', function ($options, $value) {
            return $value ?? $options['article']->getPrixDepart();
        });

        $resolver->setNormalizer('max_price', function ($options, $value) {
            return $value ?? $options['article']->getPrixFinal();
        });
    }
}