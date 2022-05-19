<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Label;
use App\Entity\Organization;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/persons")
 *
 * @IsGranted("ROLE_USER")
 */
class PersonController extends BaseController
{

    /**
     * Index Person Action
     *
     * @param PersonRepository $personRepository
     * @return Response
     *
     * @Route("", name="persons_index", methods={"GET"})
     */
    public function index(PersonRepository $personRepository): Response
    {
        $data = array();
        $data['controller_name'] = 'PersonController';
        $data['persons'] = $personRepository->findAll();

        return $this->render('dashboard/persons/index.html.twig', $data);
    }

    /**
     * Search Person Action
     *
     * @param Request $request
     * @param PersonRepository $personRepository
     *
     *
     * @Route("/search", name="persons_search", methods={"GET", "POST"})
     */
    public function search(Request $request, PersonRepository $personRepository)
    {

    }

    /**
     * Add Person Action
     *
     * @return Response
     *
     * @Route("/add", name="persons_add", methods={"GET", "POST"})
     *
     *
     */
    public function add()
    {
        $data = array();
        $data['controller_name'] = 'PersonController';
        $data['labels'] = $this->getDoctrine()->getRepository(Label::class)->findAll();
        $data['organizations'] = $this->getDoctrine()->getRepository(Organization::class)->findAll();
        $data['countries'] = $this->getDoctrine()->getRepository(Address::class)->getDistinctCountries();

        return $this->render('dashboard/persons/add.html.twig', $data);
    }


    /**
     * Details Person Action
     *
     * @param Person $person
     * @return Response
     *
     * @Route("/{id}/details", name="persons_details", methods={"GET"})
     */
    public function details(Person $person): Response
    {
        return $this->render('dashboard/persons/details.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * Store Person Action
     *
     * @param Request $request
     *
     * @Route("/store", name="persons_store", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function store(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {
        $input = array();
        $input['first_name'] = $request->get('first_name');
        $input['last_name'] = $request->get('last_name');
        $input['labels'] = $request->get('labels');
        $input['organizations'] = $request->get('organizations');
        $input['is_blacklisted'] = $request->get('is_blacklisted');
        $input['is_vip'] = $request->get('is_vip');
        $input['is_contactable'] = $request->get('is_contactable');
        $input['salutation'] = $request->get('salutation');
        $input['street'] = $request->get('street');
        $input['house_number'] = $request->get('house_number');
        $input['postal_code'] = $request->get('postal_code');
        $input['city'] = $request->get('city');
        $input['region'] = $request->get('region');
        $input['country'] = $request->get('country');


        //  TODO: Check if Person already exist
//        $check = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['name' => $input['organization_name']]);
//        if (!$check == NULL) {
//            return $this->message('danger_message', 'Person already exist!', '/organizations/add');
//        }

        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findBy(['name' => $input['organizations']]);

        $address = new Address();
        $address->setStreet($input['street']);
        $address->setHouseNumber($input['house_number']);
        $address->setPostalCode($input['postal_code']);
        $address->setCity($input['city']);
        $address->setRegion($input['region']);
        $address->setCountry($input['country']);

        $person = new Person();
        $person->setFirstName($input['first_name']);
        $person->setLastName($input['last_name']);
        $person->setAddress($address);
        $person->setSalutation($input['salutation']);

        foreach ($organizations as $organization) {
            $person->setOrganization($organization);
        }

        $violations = $validator->validate($person);

        dump($violations);
        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'persons.store.violations', 'persons_add');
        }

        $entityManager->persist($person);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'persons.store.success', 'persons_index');

    }

    /**
     * Show Person Action
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}/show", name="persons_show", methods={"GET", "POST"})
     */
    public function show($id): Response
    {
        $data = array();
        $data['controller_name'] = 'PersonController';
        $data['person'] = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $data['organizations'] = $this->getDoctrine()->getRepository(Organization::class)->findAll();
        $data['labels'] = $this->getDoctrine()->getRepository(Label::class)->findAll();
        $data['regions'] = $this->getDoctrine()->getRepository(Address::class)->getDistinctRegions();
        $data['countries'] = $this->getDoctrine()->getRepository(\App\Entity\Address::class)->getDistinctCountries();

        return $this->render('dashboard/persons/show.html.twig', $data);
    }

    /**
     * Edit Person Action
     *
     * @param Request $request
     *
     * @Route("/{id}/edit", name="persons_edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function edit(Request $request, Person $person, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {

        $input = array();
        $input['first_name'] = $request->get('first_name');
        $input['last_name'] = $request->get('last_name');
        $input['labels'] = $request->get('labels');
        $input['organizations'] = $request->get('organizations');
        $input['salutation'] = $request->get('salutation');
        $input['street'] = $request->get('street');
        $input['house_number'] = $request->get('house_number');
        $input['postal_code'] = $request->get('postal_code');
        $input['city'] = $request->get('city');
        $input['region'] = $request->get('region');
        $input['country'] = $request->get('country');

        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findBy(['name' => $input['organizations']]);

        $address = $this->getDoctrine()->getRepository(Address::class)->findOneBy(['id' => $person->getAddress()->getId()]);
        $address->setStreet($input['street']);
        $address->setHouseNumber($input['house_number']);
        $address->setPostalCode($input['postal_code']);
        $address->setCity($input['city']);
        $address->setRegion($input['region']);
        $address->setCountry($input['country']);

        $person->setFirstName($input['first_name']);
        $person->setLastName($input['last_name']);
        $person->setSalutation($input['salutation']);
        $person->setAddress($address);

        foreach ($organizations as $organization){
            $person->setOrganization($organization);
        }

        $violations = $validator->validate($person);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'persons.store.violations', 'persons_add');
        }

        $entityManager->persist($person);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'persons.edit.success', 'persons_index');
    }

    /**
     * Delete Person Action
     *
     * @param Request $request

     * @return Response
     *
     * @Route("/{id}/delete", name="persons_delete", methods={"POST"})
     */
    public function delete($id, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $person = $entityManager->getRepository(Person::class)->find($id);
        $entityManager->remove($person);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'persons.delete.success', 'persons_index');
    }
}