<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\ClientInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ClientInputFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'inputValue',
                IntegerType::class,
                [
                    'required'    => true,
                    'trim'        => true,
                    'empty_data'  => '',
                    'constraints' => [
                        new NotBlank(),
                        new Type(['type' => 'integer']),
                    ],
                    'label' => 'Please fill the number'
                ]
            )
            ->add('submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'submit btn-primary']
                ]
            );

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $formData = $event->getData();
            $formData = $this->addZeros($formData);
            $event->setData($formData);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientInput::class,
        ]);
    }


    private function addZeros(ClientInput $clientInput): ClientInput
    {
        $numberAsString = (string) $clientInput->getInput();

        $length = strlen((string) abs($clientInput->getInput()));

        if ($length < 19) {
            $zerosToAdd = 19 - $length;
            $numberAsString = $numberAsString . str_repeat('0', $zerosToAdd);
        }

        $clientInput->setInput((int)$numberAsString);

        return $clientInput;
    }
}
