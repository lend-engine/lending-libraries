<?php

namespace AppBundle\Controller\Directory;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Org;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\OrgType;
use Postmark\PostmarkClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminContactController extends Controller
{

    /**
     * @Route("/directory/admin/contact/{id}", name="admin_contact", defaults={"id" = 0}, requirements={"id": "\d+"})
     */
    public function addOrgAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = getenv('SYMFONY__POSTMARK_API_KEY');

        if (!$this->getUser()->hasRole('ROLE_ADMIN')) {
            $this->addFlash('error', "You're not admin.");
            return $this->redirectToRoute('directory');
        }

        if ($id) {
            if (!$contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id)) {
                $this->addFlash('error', "We couldn't find a contact with id {$id}.");
                return $this->redirectToRoute('directory');
            }
        } else {
            $contact = new Contact();
            $contact->setCreatedBy($this->getUser());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact->setIsActive(true);
            $contact->setUsername($contact->getEmail());
            $contact->setEmailCanonical($contact->getEmail());
            $contact->setPlainPassword(rand());

            try {
                $em->persist($contact);
                $em->flush();
                $this->addFlash('success', "Submitted ok");
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('admin_owners');
        }

        return $this->render('directory/admin_contact.html.twig', [
            'form' => $form->createView()
        ]);

    }
}