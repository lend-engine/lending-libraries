<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var \AppBundle\Repository\OrgRepository $orgRepo */
        $orgRepo = $this->getDoctrine()->getRepository('AppBundle:Org');
        $allTags = $orgRepo->getTags();

        return $this->render('directory/home.html.twig', [
            'tags' => $allTags
        ]);
    }

    /**
     * @Route("/privacy-policy", name="privacy")
     */
    public function privacyPolicyAction(Request $request)
    {
        return $this->render('default/privacy.html.twig', []);
    }

    /**
     * @Route("/terms-and-conditions", name="terms")
     */
    public function termsAction(Request $request)
    {
        return $this->render('default/terms.html.twig', []);
    }
}
