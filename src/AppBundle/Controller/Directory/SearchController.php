<?php

namespace AppBundle\Controller\Directory;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SearchController extends Controller
{
    /**
     * @Route("directory/search", name="search")
     */
    public function searchAction(Request $request)
    {
        /** @var \AppBundle\Repository\OrgSiteRepository $repo */
        $repo = $this->getDoctrine()->getRepository('AppBundle:OrgSite');

        $data = [];

        // Default to UK, 500 miles
        if (!$country = $request->get('country')) {
            $country = 'GB';
        }
        $filters['country'] = strtoupper($country);

        $searchTags = trim($request->get('tags'), ',');
        $filters['tags'] = explode(',', $searchTags);

        if (!$radius = $request->get('radius')) {
            $radius = 100;
        }

        $searchLat  = $request->get('lat');
        $searchLong = $request->get('long');

        $sites = $repo->search($filters);

        /** @var \AppBundle\Entity\OrgSite $site */
        foreach ($sites AS $site) {
            $lat  = $site->getLatitude();
            $long = $site->getLongitude();
            if ($lat && is_numeric($lat) && $long && is_numeric($long)) {
                $distance = round($this->getDistance($searchLat, $searchLong, $lat, $long), 1);
                if ($distance <= $radius) {
                    $site->setDistance($distance);

                    $website = str_replace('http://', '', $site->getOrg()->getWebsite());
                    $website = str_replace('https://', '', $website);

                    $facebook = str_replace('http://', '', $site->getOrg()->getFacebook());
                    $facebook = str_replace('https://', '', $facebook);

                    $data[] = [
                        "name" => $site->getOrg()->getName(),
                        "email" => $site->getOrg()->getEmail(),
                        "tags" => $site->getOrg()->getLends(),
                        "address" => $site->getAddress(),
                        "opening_hours" => 'hours here',
                        "website"  => $website,
                        "facebook"  => $facebook,
                        'siteId'   => $site->getId(),
                        'position' => ['lat' => (float)$lat, 'lng' => (float)$long]
                    ];
                }
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("directory/search/{siteId}", name="site_details",defaults={"siteId" = 0}, requirements={"siteId": "\d+"})
     */
    public function getSiteDetails(Request $request, $siteId)
    {
        /** @var \AppBundle\Repository\OrgSiteRepository $repo */
        $repo = $this->getDoctrine()->getRepository('AppBundle:OrgSite');

        if (!$site = $repo->find($siteId)) {
            $site = null;
        }

        return new JsonResponse($site);
    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param string $unit
     * @return float|int
     */
    private function getDistance($lat1, $lon1, $lat2, $lon2, $unit = "M") {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}