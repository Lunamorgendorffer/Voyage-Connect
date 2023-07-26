<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileUploader;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, 
    AppAuthenticator $authenticator, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //  Encoder le mot de passe brut (plain password)
            $user->setPassword( //Ici le mot de passe brut est encodé à l'aide du service de hachage de mot de passe 
                //Le mot de passe haché sera stocké dans l'entité User 
                $userPasswordHasher->hashPassword( 
                    // L'entité User à laquelle le mot de passe est associé
                    $user,
                    // Récupère le mot de passe à partir du champ 'plainPassword' du formulaire
                    $form->get('plainPassword')->getData()
                )
            );

            // image upload 
            $image = $form->get('avatar')->getData(); // get the image data
            if ($image) { // if the image is uploaded
                $imageFileName = $fileUploader->upload($image); // upload the image
                $user->setAvatar($imageFileName); // set the image
            }
            // Récupérer le nom d'utilisateur depuis le formulaire et enlever les balises HTML potentielles
            $username = strip_tags($form->get('pseudo')->getData());
            // Définir le nom d'utilisateur dans l'entité User
            $user->setPseudo($username);
            // Persiste l'entité User pour qu'elle soit préparée à être enregistrée en base de données
            $entityManager->persist($user);
            //Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@appvoyage.com', 'Admin site'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
