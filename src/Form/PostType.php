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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    private $callApiService;

    // Le constructeur accepte le service CallApiService en tant qu'argument
    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On utilise le service CallApiService pour récupérer les pays disponibles
        $countries = $this->callApiService->getCountry();

        // Définition des champs du formulaire
        $builder
            ->add('title', TextType::class,['attr'=>['class'=>'form-control"']]) // Champ titre du post
            ->add('description',TextareaType::class,['attr'=>['class'=>'form-control"']]) // Champ description du post
            ->add('image', FileType::class, [ // Champ de téléchargement de l'image du post
                'label' => 'profile Picture', // Étiquette du champ
                'mapped' => false, // Indique que ce champ n'est pas mappé directement à l'entité Post
                'required' => false, // Le champ n'est pas obligatoire
                'constraints' => [ // Contraintes sur le champ
                    new File([ // Le fichier doit respecter certaines contraintes
                        'maxSize' => '1024K', // Taille maximale du fichier
                        'mimeTypes' => [ // Types de fichiers autorisés
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload an image', // Message d'erreur si le type de fichier est invalide
                    ])
                ],
            ])
            ->add('country', ChoiceType::class, [ // Champ de sélection du pays
                'choices' => $countries, // Les options du champ sont les pays récupérés depuis le service CallApiService
                'choice_label' => function ($value, $key, $index) {
                    return $value; // Libellé de chaque option est le nom du pays
                },
                'choice_value' => function ($value) {
                    return $value; // Valeur de chaque option est le nom du pays
                },
            ])
            ->add('submit', SubmitType::class,[ // Champ de téléchargement de l'image du post
                'label' => 'Create Post',
                'attr'=>['class'=>'create-account']]) // Bouton de soumission du formulaire
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
