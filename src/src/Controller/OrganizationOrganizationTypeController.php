<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\OrganizationOrganizationType;
use App\Entity\OrganizationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/organizations")
 *
 * @IsGranted("ROLE_USER")
 */
class OrganizationOrganizationTypeController extends BaseController
{

    /**
     * Show Attach OrganizationOrganizationType Action
     *
     *
     * @return Response
     *
     * @Route("/attach", name="organizations_attach_show", methods={"GET"})
     */
    public function attach()
    {
        $data = array();
        $data['controller_name'] = 'OrganizationOrganizationTypeController';
        $data['organizations'] = $this->getDoctrine()->getRepository(Organization::class)->findAll();
        $data['organization_types'] = $this->getDoctrine()->getRepository(OrganizationType::class)->findAll();

        return $this->render('dashboard/organizations/attach.html.twig', $data);

    }

    /**
     * Store Attach OrganizationOrganizationType Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @Route("/attach/store", name="organizations_attach_store", methods={"POST"})
     */
    public function attachStore(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $input = array();
        $input['organization_id'] = $request->get('organization_id');
        $input['organization_type_id'] = $request->get('organization_type_id');

        $organization = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['id' => $input['organization_id']]);
        $organizationType = $this->getDoctrine()->getRepository(OrganizationType::class)->findOneBy(['id' => $input['organization_type_id']]);

        $organizationOrganizationType = new OrganizationOrganizationType();
        $organizationOrganizationType->setOrganization($organization);
        $organizationOrganizationType->setOrganizationType($organizationType);

        $violations = $validator->validate($organizationOrganizationType);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages', $violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'organization.attach.violations', 'organizations_attach_show');
        }

        $entityManager->persist($organizationOrganizationType);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organizations.attach.success', 'organizations_attach_show');
    }

    /**
     * Detach OrganizationOrganizationType Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @Route("/detach", name="organizations_detach_organization_type", methods={"POST"})
     */
    public function detach(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $input = array();
        $input['organization_id'] = $request->get('organization_id');
        $input['organization_type_id'] = $request->get('organization_type_id');

        $organizationsAttached =  $this->getDoctrine()->getRepository(OrganizationOrganizationType::class)->findBy(['organization' => $input['organization_id']]);

        if (empty($organizationsAttached)) {
            //TODO: fix
            return $this->message('danger_message', 'No Organization Types to detach!', '');
        }

        $id = NULL;

        foreach ($organizationsAttached as $entry) {

            $entryOrganizationTypeId = $entry->getOrganizationType()->getId();

            if ($entryOrganizationTypeId == $input['organization_type_id']) {
                $id = $entry->getId();
            }
        }

        $organizationTypeToDetach = $this->getDoctrine()->getRepository(OrganizationOrganizationType::class)->findOneBy(['id' => $id]);

        if ($organizationTypeToDetach == NULL) {
            //TODO: fix
            return new Response('Not Found', 404);
        }

        $entityManager->remove($organizationTypeToDetach);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'organization.detach.success', 'organization_types_index');
    }

}
