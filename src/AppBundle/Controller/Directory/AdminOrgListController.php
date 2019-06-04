<?php

namespace AppBundle\Controller\Directory;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminOrgListController extends Controller
{

    /**
     * @Route("/directory/admin/libraries", name="admin_libraries")
     */
    public function AdminLibraryListAction(Request $request)
    {
        /** @var \AppBundle\Repository\OrgRepository $orgRepo */
        $orgRepo = $this->getDoctrine()->getRepository('AppBundle:Org');
        $orgs = $orgRepo->findAll();

        return $this->render('directory/admin_org_list.html.twig', [
            'orgs' => $orgs
        ]);
    }
}