<?php

namespace App\Controller;

use App\Entity\ContactDetail;
use App\Entity\Label;
use App\Entity\Organization;
use App\Entity\User;
use App\Jobs\FilterSearch;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



/**
 * @Route("/organizations")
 *
 * @IsGranted("ROLE_USER")
 */
class OrganizationController extends BaseController
{
    /**
     * Index Organization Action
     *
     * @param OrganizationRepository $organizationRepository
     * @return Response
     *
     * @Route("", name="organizations_index", methods={"GET"})
     */
    public function index(OrganizationRepository $organizationRepository): Response
    {

        //$usedFilters = $this->get('session')->get('usedFilters');

        $data = array();
        $data['controller_name'] = 'OrganizationController';
        $data['organizations'] = $organizationRepository->findAll();

        $data['organization_types'] = $this->getDoctrine()->getRepository(\App\Entity\OrganizationType::class)->findAll();
        $data['countries'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctCountries();
        $data['regions'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctRegions();
        $data['labels'] = $this->getDoctrine()->getRepository(Label::class)->findAll();

        return $this->render('dashboard/organizations/index.html.twig', $data);
    }

    /**
     * Index Organization Action
     *
     * @param OrganizationRepository $organizationRepository
     * @return Response
     *
     * @Route("/ajax", name="organizations_index_ajax", methods={"GET"})
     */
    public function indexWithPaginate(Request $request, OrganizationRepository $organizationRepository)
    {

        $data = array();
        $data['controller_name'] = 'OrganizationController';
        $data['draw'] = $request->query->get('draw');
        $data['columns'] = $request->query->get('columns');
        $data['order'] = $request->query->get('order');
        $data['start'] = $request->query->get('start');
        $data['length'] = $request->query->get('start');
        $data['search'] = $request->query->get('search');


        $data['organizations'] = $organizationRepository->findAllWithPaginator('id', 'ASC', 30);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonObject = $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $dirtyDataToResponse = json_decode($jsonObject);


        $dataToResponse['draw'] = 1;
        $dataToResponse['recordsTotal'] = 1500;
        $dataToResponse['recordsFiltered'] = 1000;
        $dataToResponse['data'] = $dirtyDataToResponse->organizations->data;
        $dataToResponse['error'] = '';

        $response = new JsonResponse($dataToResponse);

        return $response;
    }

    /**
     * Search Organization Action
     *
     * @param Request $request
     * @param OrganizationRepository $organizationRepository
     *
     *
     * @Route("/search", name="organizations_search", methods={"GET", "POST"})
     */
    public function search(Request $request, OrganizationRepository $organizationRepository)
    {
        $input =  $request->get('search_form');

        $organizations = $organizationRepository->findBySearchQuery($input);

        return $this->render('dashboard/organizations/search.html.twig', ['organizations' => $organizations]);

    }

    /**
     * Add Organization Action
     *
     * @return Response
     *
     * @Route("/add", name="organizations_add", methods={"GET", "POST"})
     *
     *
     */
    public function add()
    {
        $data = array();
        $data['controller_name'] = 'OrganizationController';
        $data['organization_types'] = $this->getDoctrine()->getRepository(\App\Entity\OrganizationType::class)->findAll();
        $data['regions'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctRegions();
        $data['countries'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctCountries();

        return $this->render('dashboard/organizations/add.html.twig', $data);
    }

    /**
     * Details Organization Action
     *
     * @param Organization $organization
     * @return Response
     *
     * @Route("/{id}/details", name="organizations_details", methods={"GET"})
     */
    public function details(Organization $organization): Response
    {
        return $this->render('dashboard/organizations/details.html.twig', [
            'organization' => $organization,
        ]);
    }

    /**
     * Store Organization Action
     *
     * @param Request $request
     *
     * @Route("/store", name="organizations_store", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function store(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {
        $input = array();
        $input['organization_name'] = $request->get('organization_name');
        $input['organization_type'] = $request->get('organization_type');
        $input['telephone_number'] = $request->get('telephone_number');
        $input['fax'] = $request->get('fax');
        $input['internet_site'] = $request->get('internet_site');
        $input['email'] = $request->get('email');
        $input['street'] = $request->get('street');
        $input['house_number'] = $request->get('house_number');
        $input['postal_code'] = $request->get('postal_code');
        $input['city'] = $request->get('city');
        $input['region'] = $request->get('region');
        $input['country'] = $request->get('country');

        // Check if Organization already exist
        $check = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['name' => $input['organization_name']]);
        if (!$check == NULL) {
            return $this->message('danger_message', 'Organization already exist!', '/organizations/add');
        }

        // Persist new Organization
        $organizationType = $this->getDoctrine()->getRepository(\App\Entity\OrganizationType::class)->findOneBy(['name' => $input['organization_type'] ]);

        $contactDetails = new ContactDetail();
        $contactDetails->setTelephoneNumber($input['telephone_number']);
        $contactDetails->setFax($input['fax']);
        $contactDetails->setInternetSite($input['internet_site']);
        $contactDetails->setEmail($input['email']);
        $timezone = new \DateTimeZone('Europe/Berlin');
        $nowImmutable = new \DateTimeImmutable('now', $timezone );
        $contactDetails->setModifiedAt($nowImmutable);

        $address = new \App\Entity\Address();
        $address->setStreet($input['street']);
        $address->setHouseNumber($input['house_number']);
        $address->setPostalCode($input['postal_code']);
        $address->setCity($input['city']);
        $address->setRegion($input['region']);
        $address->setCountry($input['country']);

        $organization = new Organization();
        $organization->setName($input['organization_name']);
        $organization->setOrganizationType($organizationType);


        //TODO:PERSONS
//        foreach ($labels as $label) {
//            $organization->addLa($project);
//        }

        $organization->setContactDetail($contactDetails);
        $organization->setAddress($address);

        $violations = $validator->validate($organization);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'organizations.store.violations', 'organizations_add');
        }

        $entityManager->persist($organization);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organizations.store.success', 'organizations_index');
    }

    /**
     * Show Organization Action
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}/show", name="organizations_show", methods={"GET", "POST"})
     */
    public function show($id): Response
    {
        $data = array();
        $data['controller_name'] = 'OrganizationController';
        $data['organization'] = $this->getDoctrine()->getRepository(Organization::class)->find($id);
        $data['countries'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctCountries();

        return $this->render('dashboard/organizations/show.html.twig', $data);
    }

    /**
     * Edit Organization Action
     *
     * @param Request $request
     *
     * @Route("/{id}/edit", name="organizations_edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function edit(Request $request, Organization $organization, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {
        $input = array();
        $input['organization_name'] = $request->get('organization_name');
        $input['organization_type'] = $request->get('organization_type');
        $input['telephone_number'] = $request->get('telephone_number');
        $input['fax'] = $request->get('fax');
        $input['internet_site'] = $request->get('internet_site');
        $input['email'] = $request->get('email');
        $input['street'] = $request->get('street');
        $input['house_number'] = $request->get('house_number');
        $input['postal_code'] = $request->get('postal_code');
        $input['city'] = $request->get('city');
        $input['region'] = $request->get('region');
        $input['country'] = $request->get('country');

        // Persist edited Organization
        $organizationType = $this->getDoctrine()->getRepository(\App\Entity\OrganizationType::class)->findOneBy(['name' => $input['organization_type']]);

        $contactDetails = $this->getDoctrine()->getRepository(ContactDetail::class)->findOneBy(['id' => $organization->getContactDetail()->getId()]);
        $contactDetails->setTelephoneNumber($input['telephone_number']);
        $contactDetails->setFax($input['fax']);
        $contactDetails->setInternetSite($input['internet_site']);
        $contactDetails->setEmail($input['email']);

        $timezone = new \DateTimeZone('Europe/Berlin');
        $nowImmutable = new \DateTimeImmutable('now', $timezone );

        $contactDetails->setModifiedAt($nowImmutable);


        $address = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->findOneBy(['id' => $organization->getAddress()->getId()]);
        $address->setStreet($input['street']);
        $address->setHouseNumber($input['house_number']);
        $address->setPostalCode($input['postal_code']);
        $address->setCity($input['city']);
        $address->setRegion($input['region']);
        $address->setCountry($input['country']);

        $organization->setName($input['organization_name']);
        $organization->setOrganizationType($organizationType);

//        foreach ($projects as $project) {
//            $organization->addProject($project);
//        }

        $organization->setContactDetail($contactDetails);
        $organization->setAddress($address);

        $violations = $validator->validate($organization);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'organizations.store.violations', 'organizations_index');
        }

        $entityManager->persist($organization);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organizations.edit.success', 'organizations_index');
    }

    /**
     * Delete Organization Action
     *
     * @param Request $request
     * @param User $user
     * @return Response
     *
     * @Route("/{id}/delete", name="organizations_delete", methods={"POST"})
     */
    public function delete($id, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $organization = $entityManager->getRepository(Organization::class)->find($id);
        $entityManager->remove($organization);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organizations.delete.success', 'organizations_index');
    }

}
