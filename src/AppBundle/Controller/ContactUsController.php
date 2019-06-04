<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactUsType;
use Postmark\PostmarkClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactUsController extends Controller
{

    /**
     * @Route("/contact", name="contact_us")
     */
    public function contactForm(Request $request)
    {
        $key = getenv('SYMFONY__POSTMARK_API_KEY');

        $form = $this->createForm(ContactUsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $subject    = $form->get('subject')->getData();
            $fromEmail  = $form->get('email')->getData();
            $message    = $form->get('message')->getData();
            $library    = $form->get('library')->getData();

            try {

                $client = new PostmarkClient($key);
                $message = $this->renderView(
                    'emails/contact.html.twig',
                    [
                        'message' => $message,
                        'library' => $library,
                        'from'    => $fromEmail
                    ]
                );
                $client->sendEmail(
                    "hello@lend-engine.com",
                    "chris@lend-engine.com",
                    $subject,
                    $message,
                    null,
                    null,
                    true,
                    $fromEmail
                );

                $this->addFlash('success', "Thanks!");

                return $this->redirectToRoute('contact_us');

            } catch (\Exception $generalException) {

                $this->addFlash('error', 'Failed to send email:' . $generalException->getMessage());

            }

        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);

    }
}