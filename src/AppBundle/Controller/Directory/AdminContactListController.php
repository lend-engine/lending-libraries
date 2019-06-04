<?php

namespace AppBundle\Controller\Directory;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminContactListController extends Controller
{

    /**
     * @Route("/directory/admin/owners", name="admin_owners")
     */
    public function AdminLibraryListAction(Request $request)
    {
        /** @var \AppBundle\Repository\ContactRepository $contactRepo */
        $contactRepo = $this->getDoctrine()->getRepository('AppBundle:Contact');
        $contacts = $contactRepo->findAll();

        return $this->render('directory/admin_owner_list.html.twig', [
            'contacts' => $contacts
        ]);
    }
}