<?php

namespace App\Form;

use App\Entity\TaxBand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TaxBandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lowerLimit', NumberType::class, [
                'label' => 'Lower Limit',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Lower limit should not be blank.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+(\.[0-9]{1,2})?$/',
                        'message' => 'Lower limit should be a valid positive number with up to 2 decimal places.',
                    ]),
                ],
            ])
            ->add('upperLimit', NumberType::class, [
                'label' => 'Upper Limit (Leave empty for no upper limit)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+(\.[0-9]{1,2})?$/',
                        'message' => 'Upper limit should be a valid positive number with up to 2 decimal places.',
                    ]),
                ],
            ])
            ->add('rate', NumberType::class, [
                'label' => 'Tax Rate (%)',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Rate should not be blank.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+(\.[0-9]{1,2})?$/',
                        'message' => 'Rate should be a valid positive percentage with up to 2 decimal places.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaxBand::class,
        ]);
    }
}
