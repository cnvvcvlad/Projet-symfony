<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator, \Swift_Mailer $mailer, TranslatorInterface $translator): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setInscriptionDateAt(new \DateTime());

            // encode the plain password
            // on défini l'encodage du mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

            // On génère un token et on l'enregistre
            $user->setActivationToken(md5(uniqid()));

            // on enregistre les informations dans la base de données pour l'utilisateur qui vient de s'inscrire
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            // on crée un message
            $message = (new \Swift_Message('Activate your account'))
                // on attribue l'expediteur, l'addrese specifique de notre site
                ->setFrom('cnvvc_vlad@yahoo.fr')
                // on attribue le destinataire
                ->setTo($user->getEmail())
                //on crée le contenu
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    // on indique le dernier parametre
                    'text/html'
                );

            // on envoie le mail
            $mailer->send($message);


            // une fois inscrit on se connecte directement

//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );

            $translated_message = $translator->trans('User successfully registered');
            // On génère un message pour la view
            $this->addFlash('message', $translated_message);

            // On retourne au formulaire de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, TranslatorInterface $translator)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if (!$user) {
            $translated_message = $translator->trans('This user does not exist');
            // On renvoie une erreur 404
            throw $this->createNotFoundException($translated_message);
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


        $translated_message = $translator->trans('User successfully activated');
        // On génère un message
        $this->addFlash('message', $translated_message);

        // On retourne à l'accueil
        return $this->redirectToRoute('accueil');
    }

}
