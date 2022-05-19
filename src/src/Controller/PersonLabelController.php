<?php

namespace App\Controller;

use App\Entity\Label;
use App\Entity\Person;
use App\Entity\PersonLabel;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/persons")
 *
 *
 */
class PersonLabelController extends BaseController
{

    /**
     * Show Attach PersonLabel Action
     *
     *
     * @return Response
     *
     * @Route("/attach", name="persons_attach_show", methods={"GET"})
     */
    public function attach()
    {
        $data = array();
        $data['controller_name'] = 'PersonLabelController';
        $data['persons'] = $this->getDoctrine()->getRepository(Person::class)->findAll();
        $data['labels'] = $this->getDoctrine()->getRepository(Label::class)->findAll();

        return $this->render('dashboard/persons/attach.html.twig', $data);

    }

    /**
     * Store Attach PersonLabel Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @Route("/attach/store", name="persons_attach_store", methods={"POST"})
     */
    public function attachStore(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $input = array();
        $input['person_id'] = $request->get('person_id');
        $input['label_id'] = $request->get('label_id');
        $input['is_blacklisted'] = $request->get('is_blacklisted');
        $input['is_vip'] = $request->get('is_vip');
        $input['is_contactable'] = $request->get('is_contactable');

        $person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(['id' => $input['person_id']]);
        $label = $this->getDoctrine()->getRepository(Label::class)->findOneBy(['id' => $input['label_id']]);

        // Check if Label is already attached
        $check = $this->getDoctrine()->getRepository(PersonLabel::class)->findOneBy(['person' => $input['person_id'], 'label' => $input['label_id']]);
        if (!$check == NULL) {

            $response = [
                'success' => false,
                'data'    => array(),
                'message' => 'error during attach',
            ];

            return new JsonResponse($response, 422);
        }

        $personLabel = new PersonLabel();
        $personLabel->setPerson($person);
        $personLabel->setLabel($label);
        $personLabel->setIsBlacklisted($input['is_blacklisted']);
        $personLabel->setIsVIP($input['is_vip']);
        $personLabel->setIsContactable($input['is_contactable']);

        $violations = $validator->validate($personLabel);

        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages', $violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'person_label.store.violations', 'persons_attach_show');
        }

        $entityManager->persist($personLabel);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'person.attach.success', 'persons_attach_show');
    }

    /**
     * Detach PersonLabel Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @Route("/detach", name="persons_detach_label", methods={"POST"})
     */
    public function detach(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $input = array();
        $input['person_id'] = $request->get('person_id');
        $input['label_id'] = $request->get('label_id');

        $personsAttached =  $this->getDoctrine()->getRepository(PersonLabel::class)->findBy(['person' => $input['person_id']]);

        if (empty($personsAttached)) {
            //TODO: fix
            return $this->message('danger_message', 'No Labels to detach!', '');
        }

        $id = NULL;

        foreach ($personsAttached as $entry) {

            $entryLabelId = $entry->getLabel()->getId();

            if ($entryLabelId == $input['label_id']) {
                $id = $entry->getId();
            }
        }

        $labelToDetach = $this->getDoctrine()->getRepository(PersonLabel::class)->findOneBy(['id' => $id]);

        if ($labelToDetach == NULL) {
            //TODO: fix
            return new Response('Not Found', 404);
        }

        $entityManager->remove($labelToDetach);
        $entityManager->flush();

        return  $this->transmessageWithId($translator,'success_message', array(), 'dashboard', 'label.detach.success', 'labels_details', $input['label_id']);

    }

}
