<?php

namespace AppBundle\Controller\Directory;

use AppBundle\Entity\OrgSite;
use AppBundle\Form\Type\OrgSiteType;
use Postmark\PostmarkClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrgSiteController extends Controller
{

    /**
     * @Route("/directory/org/{id}/site/{siteId}", name="site", defaults={"siteId" = 0}, requirements={"id": "\d+", "siteId": "\d+"})
     */
    public function addSiteAction(Request $request, $id, $siteId)
    {
        $em = $this->getDoctrine()->getManager();
        $key = getenv('SYMFONY__POSTMARK_API_KEY');

        // Verification
        if ($id) {
            if (!$org = $this->getDoctrine()->getRepository('AppBundle:Org')->find($id)) {
                $this->addFlash('error', "We couldn't find an organisation with id {$id}.");
                return $this->redirectToRoute('directory');
            }

            if ($org->getOwner() != $this->getUser() && !$this->getUser()->hasRole('ROLE_ADMIN')) {
                $this->addFlash('error', "You're not the owner of that organisation.");
                return $this->redirectToRoute('directory');
            }
        } else {
            $this->addFlash('error', "An organisation is needed to create a site.");
            return $this->redirectToRoute('directory');
        }

        if ($siteId) {
            $mode = 'edit';
            if (!$orgSite = $this->getDoctrine()->getRepository('AppBundle:OrgSite')->find($siteId)) {
                $this->addFlash('error', "We couldn't find a site with id {$siteId}.");
                return $this->redirectToRoute('directory');
            }
        } else {
            $mode = 'create';
            $orgSite = new OrgSite();
            $orgSite->setOrg($org);
        }

        $form = $this->createForm(OrgSiteType::class, $orgSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($orgSite);
                $em->flush();
//                if (!$orgSite->getLatitude() || $orgSite->getLongitude()) {
//                    $this->addFlash("error", "We couldn't determine the position of that site from the address so it won't appear on the directory map.");
//                }
                if ($mode == 'edit') {
                    $this->addFlash('success', "Site details were updated OK.");
                } else {
                    $this->addFlash('success', "A site was added!");
                }
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
            if ($this->getUser()->hasRole('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_libraries');
            }
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('directory/org_site.html.twig', [
            'form'    => $form->createView(),
            'org'     => $org,
            'siteId'  => $siteId,
            'mode'    => $mode
        ]);

    }
}