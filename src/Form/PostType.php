<?php

namespace App\Form;

use App\Entity\Post;
use App\Service\CallApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    
    private $callApiService;

    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countries = $this->callApiService->getCountry();
        // dd($this->callApiService->getCountry());
        // $countryChoices = [];

        // foreach ($countries as $country) {
        //     $countryChoices[$country['name']['common']] = $country['name']['common'];
        // }

        $builder
            ->add('title')
            ->add('description')
            ->add('image', FileType::class,[
                'label' => 'profile Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024K',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload an image',
                    ])
                ],
            ])
            ->add('country', ChoiceType::class, [
            //    'class'=>CallApiService::class,
                'label_attr' => $countries
                // 'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            // 'data_class' => CallApiService::class,
        ]);
    }
}
