<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\ContactDetail;
use App\Entity\Organization;
use App\Entity\OrganizationOrganizationType;
use App\Entity\OrganizationType;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 *
 *
 */
class ApplicationController extends BaseController
{
    /**
     * @Route("/", name="app_dashboard")
     */
    public function index(): Response
    {
        $data = array();
        $data['controller_name'] = 'ApplicationController';
        $data['user'] = $this->getUser();

        return $this->render('dashboard/index.html.twig', $data);
    }


    /**
     * @Route("/test", name="test")
     */
    public function test(EntityManagerInterface $entityManager)
    {
        $csv = array();
        $i = 0;
        $checkOrganizationType = [];
        if (($handle = fopen('/var/www/app/berlin.csv', "r")) !== false) {
            $columns = fgetcsv($handle, 1000, ",");


            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $csv[$i] = array_combine($columns, $row);
                $i++;
            }

            fclose($handle);
        }

        foreach ($csv as $entry) {

            $regex = '/^([^\d]*[^\d\s]) *(\d.*)$/';
            $addressExploded = explode(',', $entry['address']);
            preg_match($regex, $addressExploded[0], $matches);
            array_splice($matches, 0, 1);

            $arr = $matches;
            $arr1 = explode(' ', $addressExploded[1]);
            array_splice($arr1, 0, 1);

            $result = array_merge($arr, $arr1);

            $keys = ['street', 'house_number', 'postal_code', 'city'];

            try {
                $address = array_combine($keys, $result);
            }catch (\Exception $e) {
                
            }


            $entry['address'] =  $address;

            $addressToStore = new Address();

            if ($entry['state'] === 'BE') {
                $addressToStore->setCountry('Germany');
                $addressToStore->setRegion('Berlin');
                $addressToStore->setCity('Berlin');
            }
            $addressToStore->setPostalCode($entry['address']['postal_code']);
            $addressToStore->setHouseNumber($entry['address']['house_number']);
            $addressToStore->setStreet($entry['address']['street']);

            $contactDetailsToStore = new ContactDetail();
            $contactDetailsToStore->setFax("030" . $entry['fax']);
            $contactDetailsToStore->setTelephoneNumber('030' . $entry['phone']);
            $contactDetailsToStore->setInternetSite('www.helliwood.de');
            $contactDetailsToStore->setEmail('helliwood@helliwood.de');

            //dd($check);
            $check = $entityManager->getRepository(OrganizationType::class)->findOneBy(['name' =>  $entry['school_type']]);

            if (!isset($checkOrganizationType[$entry['school_type']])) {

                $checkOrganizationType[$entry['school_type']] = 1;
                dump('checkis null');
                $organizationTypeToStore = new OrganizationType();
                $organizationTypeToStore->setName($entry['school_type']);
            }


            if ($check != NULL && $check->getName() === $entry['school_type']) {
                $organizationTypeToStore = $check;
            }

            $organizationToStore = new Organization();
            $organizationToStore->setName($entry['name']);
            $organizationToStore->setAddress($addressToStore);
            $organizationToStore->setContactDetail($contactDetailsToStore);

            $organzationOrganizationTypeToStore =  new OrganizationOrganizationType();
            $organzationOrganizationTypeToStore->setOrganization($organizationToStore);
            $organzationOrganizationTypeToStore->setOrganizationType($organizationTypeToStore);

            $entityManager->persist($organizationToStore);
            $entityManager->persist($organizationTypeToStore);

        }

        $entityManager->flush();
        dd();



    }

}
