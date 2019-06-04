<?php

namespace AppBundle\Controller\Directory;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrgSiteDeleteController extends Controller
{

    /**
     * @Route("/directory/org/{id}/site/{siteId}/delete", name="site_delete", defaults={"siteId" = 0}, requirements={"id": "\d+", "siteId": "\d+"})
     */
    public function siteDeleteAction(Request $request, $id, $siteId)
    {
        $em = $this->getDoctrine()->getManager();

        // Verification
        if ($id) {
            if (!$org = $this->getDoctrine()->getRepository('AppBundle:Org')->find($id)) {
                $this->addFlash('error', "We couldn't find an organisation with id {$id}.");
                return $this->redirectToRoute('directory');
            }

            if ($org->getOwner() != $this->getUser()) {
                $this->addFlash('error', "You're not the owner of that organisation.");
                return $this->redirectToRoute('directory');
            }
        } else {
            $this->addFlash('error', "No organisation ID given.");
            return $this->redirectToRoute('directory');
        }

        if (!$orgSite = $this->getDoctrine()->getRepository('AppBundle:OrgSite')->find($siteId)) {
            $this->addFlash('error', "No site found with ID {$siteId}.");
            return $this->redirectToRoute('directory');
        }

        $em->remove($orgSite);
        $em->flush();

        $this->addFlash('success', "Site removed OK");
        return $this->redirectToRoute('fos_user_profile_show');
    }
}