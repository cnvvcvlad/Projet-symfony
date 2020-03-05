<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // Ici nous enverrons l'e-mail
            // dd($contact);

            $message = (new \Swift_Message('Nouveau Contact'))
                // on attribue l'expediteur
                ->setFrom($contact['email'])
                // on attribue le destinataire
                ->setTo('cnvvc_vlad@yahoo.fr')
                // on crée le message la vue twig
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig', compact('contact')
                    ),
                    'text/html'
                );

            // on envoie le message
            $mailer->send($message);

            $translated_message = $translator->trans('Your message has been sent, we will respond to you as soon as possible.');

                // Permet un message flash de renvoyer
            $this->addFlash('message', $translated_message);

//            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');



            return $this->redirectToRoute('accueil');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
