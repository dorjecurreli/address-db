<?php

namespace App\Controller;

use App\Entity\OrganizationType;
use App\Repository\OrganizationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/organization-types")
 *
 * @IsGranted("ROLE_USER")
 */
class OrganizationTypeController extends BaseController
{

    /**
     * Index Organization Type Action
     *
     * @param OrganizationTypeRepository $organizationTypeRepository
     * @return Response
     *
     * @Route("", name="organization_types_index", methods={"GET"})
     */
    public function index(OrganizationTypeRepository $organizationTypeRepository): Response
    {
        $data = array();
        $data['controller_name'] = 'OrganizationTypeController';
        $data['organization_types'] = $organizationTypeRepository->findAll();

        return $this->render('dashboard/organization-types/index.html.twig', $data);
    }

    /**
     * Search Organization Type Action
     *
     * @param Request $request
     * @param OrganizationTypeRepository $organizationTypeRepository
     *
     *
     * @Route("/search", name="organization_types_search", methods={"GET", "POST"})
     */
    public function search(Request $request, OrganizationTypeRepository $organizationTypeRepository)
    {

    }

    /**
     * Add Organization Type Action
     *
     * @return Response
     *
     * @Route("/add", name="organization_types_add", methods={"GET", "POST"})
     *
     *
     */
    public function add()
    {
        $data = array();
        $data['controller_name'] = 'OrganizationTypeController';

        return $this->render('dashboard/organization-types/add.html.twig', $data);
    }


    /**
     * Details Organization Type Action
     *
     * @param OrganizationType $organizationType
     * @return Response
     *
     * @Route("/{id}/details", name="organization_types_details", methods={"GET"})
     */
    public function details(OrganizationType $organizationType): Response
    {
        //dd($organizationType->getOrganizationOrganizationType()->getValues());
        return $this->render('dashboard/organization-types/details.html.twig', [
            'organizationType' => $organizationType,
        ]);
    }

    /**
     * Store Organization Type Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @Route("/store", name="organization_types_store", methods={"POST"})
     *
     * @return Response
     */
    public function store(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {
        $input = array();
        $input['name'] = $request->get('naem');
        dump($input);

        $check = $this->getDoctrine()->getRepository(OrganizationType::class)->findOneBy(['name' => $input['name']]);
        if (!$check == NULL) {
            return $this->message('danger_message', 'Organization Type already exist!', '/organization-types/add');
        }

        $organizationType = new OrganizationType();
        $organizationType->setName($input['name']);

        $violations = $validator->validate($organizationType);

        dump($violations);
        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'organization_type.store.violations', 'organization_types_add');
        }

        $entityManager->persist($organizationType);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organization_type.store.success', 'organization_types_index');

    }

    /**
     * Show Organization Type Action
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}/show", name="organization_type_show", methods={"GET", "POST"})
     */
    public function show($id): Response
    {
        $data = array();
        $data['controller_name'] = 'OrganizationTypeController';
        $data['organization_type'] = $this->getDoctrine()->getRepository(OrganizationType::class)->find($id);;

        return $this->render('dashboard/organization-types/show.html.twig', $data);
    }


    /**
     * Edit Organization Type Action
     *
     * @param Request $request
     * @param OrganizationType $organizationType
     *
     * @Route("/{id}/edit", name="organization_types_edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function edit(Request $request, OrganizationType $organizationType, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {

        $input = array();
        $input['name'] = $request->get('name');

        $organizationType->setName($input['name']);

        $violations = $validator->validate($organizationType);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'organization_type.edit.violations', 'organization_types_add');
        }

        $entityManager->persist($organizationType);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organization_type.edit.success', 'organization_types_index');
    }

    /**
     * Delete Organization Type Action
     *
     * @param Request $request
     * @param OrganizationType $organizationType
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     *
     * @Route("/{id}/delete", name="organization_types_delete", methods={"POST"})
     */
    public function delete(OrganizationType $organizationType, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($organizationType);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organization_type.delete.success', 'organization_types_index');
    }








}