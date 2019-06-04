<?php

namespace AppBundle\Controller\Directory;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Org;
use AppBundle\Entity\OrgSite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ImportController extends Controller
{

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @Route("directory/admin/import", name="import")
     */
    public function importAction(Request $request)
    {

        $user = $this->getUser();
        $formBuilder = $this->createFormBuilder();
        $em = $this->getDoctrine()->getManager();

        $header = [];

        $formBuilder->add('csv_data', TextareaType::class, array(
            'label' => 'Paste tab separated data, one item per line.',
            'attr' => array(
                'rows' => 20,
                'data-help' => ''
            )
        ));

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Read the CSV
            $csv_data = $form->get('csv_data')->getData();
            $rows = explode("\n",$csv_data);

            // Get a mapping of column names to index, eg "Code" = 2
            $created = 0;

            foreach ($rows AS $rowId => $row) {

                // Get the item
                $item = str_getcsv($row, "\t");

                // Pad out the row data if any columns were empty, and validate cells
                $rowOk = true;
                foreach ($header AS $i => $key) {
                    if (!isset($item[$i])) {
                        $item[$i] = '';
                    }
                    // Validate the cell, returning data type ready for the setter (eg tag ID)
                    $cellType = $this->getCellType($key);
                    $originalData = $item[$i];
                    $cleanedData = $this->validateCell($originalData, $cellType);
                    if (is_array($cleanedData)) {
                        $this->addFlash('error', "Line {$rowId} contains bad data. '{$originalData}' for $key is not {$cellType}");
                        $rowOk = false;
                    } else {
                        $item[$i] = $cleanedData;
                    }
                }

                if ($rowOk == false) {
                    continue;
                }

                if ($org = $em->getRepository("AppBundle:Org")->findOneBy(['name' => $item[3]])) {
                    $contact = $org->getOwner();
                    continue;
                } else {
                    $org = new Org();
                    $org->setCreatedBy($this->getUser());

                    if ($item[2]) {
                        $contact = new Contact();
                        $contact->setCreatedBy($this->getUser());
                        $contact->setPlainPassword(rand());
                        $contact->setUsername($item[2]);

                        $contact->setFirstName($item[0]);
                        $contact->setLastName($item[1]);
                        $contact->setEmail($item[2]);
                    }
                }

                if (isset($contact)) {
                    $org->setOwner($contact);
                }

                $org->setName($item[3]);
                $org->setWebsite($item[4]);
                $org->setEmail($item[5]);
                $org->setLends($item[6]);
                $org->setStatus(Org::STATUS_ACTIVE);
                $org->setFacebook($item[11]);

                if (!$item[9]) {
                    $item[9] = '-';
                }

                if (!$site = $em->getRepository("AppBundle:OrgSite")->findOneBy(['name' => $item[7], 'org' => $org])) {
                    $site = new OrgSite();
                    $site->setOrg($org);
                    $site->setName($item[7]);
                    $site->setAddress($item[8]);
                    $site->setPostcode($item[9]);
                    $site->setCountry($item[10]);
                    $site->setLatitude($item[12]);
                    $site->setLongitude($item[13]);
                }

                $created++;

                if (isset($contact)) {
                    $em->persist($contact);
                }
                $em->persist($org);
                $em->persist($site);
            }

            // We could flush at the end, creating all items in one go, but as we are creating
            // categories etc on the fly, we need to persist for each product in the CSV
            try {
                $em->flush();
                if ($created > 0) {
                    $this->addFlash('success', $created.' created.');
                }
            } catch (\Exception $generalException) {
                $this->addFlash('error', 'Failed to save: '.$generalException->getMessage());
            }

            return $this->redirectToRoute('import');
        }

        return $this->render('directory/import.html.twig', [
            'form' => $form->createView(),
            'headerKeys' => $this->getHeaderKeys()
        ]);
    }

    private function validateCell($data, $type)
    {
        switch ($type) {
            case 'text':
                if ($data) {
                    return $data;
                }
                break;
            case 'number':
                if (!is_numeric($data)) {
                    return array(
                        'error' => "Cell validation error : {$data} is not {$type}"
                    );
                } else {
                    return (float)$data;
                }
                break;
            case 'integer':
                if (!is_numeric($data)) {
                    return array(
                        'error' => "Cell validation error : {$data} is not {$type}"
                    );
                } else {
                    return (int)$data;
                }
                break;
            case 'boolean':
                if (strtolower($data) == 'yes') {
                    return 1;
                } else if (strtolower($data) == 'no') {
                    return 0;
                } else {
                    return array(
                        'error' => "Cell validation error : {$data} is not {$type}"
                    );
                }
                break;
        }

        return null;
    }

    private function getCellType($k)
    {
        $validations = [
            'FirstName' => 'text',
            'LastName' => 'text',
            'Email' => 'text',
            'OrgName' => 'text',
            'OrgWebsite' => 'text',
            'OrgEmail' => 'text',
            'OrgLends' => 'text',
            'SiteName' => 'text',
            'SiteAddress' => 'text',
            'SitePostcode' => 'text',
            'SiteCountry' => 'text',
        ];

        if (!isset($validations[$k])) {
            $this->addFlash("error", 'Column '.$k.' is not valid');
            return false;
        }
        return $validations[$k];
    }

    /**
     * @return array
     */
    private function getHeaderKeys()
    {
        $validHeaderKeys = [
            'FirstName' => 'text',
            'LastName' => 'text',
            'Email' => 'text',
            'OrgName' => 'text',
            'OrgWebsite' => 'text',
            'OrgEmail' => 'text',
            'OrgLends' => 'text',
            'SiteName' => 'text',
            'SiteAddress' => 'text',
            'SitePostcode' => 'text',
            'SiteCountry' => 'text',
        ];

        return $validHeaderKeys;
    }

}