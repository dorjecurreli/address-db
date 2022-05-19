<?php

namespace App\Controller;

use App\Entity\Label;
use App\Entity\Organization;
use App\Repository\LabelRepository;
use App\Repository\PersonLabelRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/labels")
 *
 *
 */
class LabelController extends BaseController
{
    /**
     * Index Label Action
     *
     * @param LabelRepository $labelRepository
     * @return Response
     *
     * @Route("", name="labels_index", methods={"GET"})
     */
    public function index(LabelRepository $labelRepository, PersonLabelRepository $personLabelRepository): Response
    {
        $data = array();
        $data['controller_name'] = 'LabelController';
        $data['labels'] = $labelRepository->findAll();
        $data['person_label'] = $personLabelRepository->findAll();

        return $this->render('dashboard/labels/index.html.twig', $data);
    }

    /**
     * Search Labels Action
     *
     * @param LabelRepository $labelRepository
     * @param Request $request
     *
     *
     * @Route("/search", name="labels_search", methods={"GET", "POST"})
     */
    public function search(Request $request, LabelRepository $labelRepository)
    {
        $input =  $request->get('search_form');

        $labels = $labelRepository->findBySearchQuery($input);

        return $this->render('dashboard/projects/search.html.twig', ['labels' => $labels]);

    }




    /**
     * Search Ajax Labels Action
     *
     * @param LabelRepository $labelRepository
     * @param Request $request
     * @param Serializer $serializer
     *
     * @return mixed
     *
     *
     * @Route("/search/ajax", name="labels_search_ajax", methods={"GET"})
     */
    public function searchAjax(Request $request, LabelRepository $labelRepository)
    {
        $query =  $request->query->get('search');

        $labels = $labelRepository->findBySearchQuery($query);

        $data_labels = array();

        foreach ($labels as $key => $label) {
            $data_labels[$key]['id'] = $label->getId();
            $data_labels[$key]['name'] = $label->getName();
            $data_labels[$key]['color'] = $label->getColor();
            $data_labels[$key]['icon'] = $label->getIcon();
            $data_labels[$key]['description'] = $label->getDescription();
        }

        $data = array();
        $data['labels'] = $data_labels;

        return new JsonResponse($data);

    }



    /**
     * Add Label Action
     *
     * @return Response
     *
     * @Route("/add", name="labels_add", methods={"GET", "POST"})
     *
     *
     */
    public function add()
    {
        $data = array();
        $data['controller_name'] = 'LabelController';

        return $this->render('dashboard/labels/add.html.twig', $data);
    }

    /**
     * Details Labels Action
     *
     * @param Label $label
     * @return Response
     *
     * @Route("/{id}/details", name="labels_details", methods={"GET"})
     */
    public function details(Label $label): Response
    {
        return $this->render('dashboard/labels/details.html.twig', [
            'label' => $label
        ]);
    }


    /**
     * Store Label Action
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @Route("/store", name="labels_store", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function store(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {
        $input = array();
        $input['label_name'] = $request->get('label_name');
        $input['label_color'] = $request->get('label_color');
        $input['label_icon'] = $request->get('label_icon');
        $input['label_description'] = $request->get('label_description');

        // Check if Label already exist
        $check = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['name' => $input['label_name']]);
        if (!$check == NULL) {
            return $this->message('danger_message', 'Label already exist!', '/labels/add');
        }

        // Persist new Label
        $label = new Label();
        $label->setName($input['label_name']);
        $label->setColor($input['label_color']);
        $label->setIcon($input['label_icon']);
        $label->setDescription($input['label_description']);

        $violations = $validator->validate($label);

        dump($violations);
        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'labels.store.violations', 'labels_add');
        }

        $entityManager->persist($label);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'labels.store.success', 'labels_add');
    }

    /**
     * Show Label Action
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}/show", name="labels_show", methods={"GET", "POST"})
     */
    public function show($id): Response
    {
        $label = $this->getDoctrine()->getRepository(Label::class)->find($id);
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        $data = array();
        $data['controller_name'] = 'LabelsController';
        $data['label'] = $label;
        $data['organizations'] = $organizations;

        return $this->render('dashboard/labels/show.html.twig', $data);
    }

    /**
     * Edit Label Action
     *
     * @param Request $request
     * @param Label $label
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     *
     * @Route("/{id}/edit", name="labels_edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function edit(Request $request, Label $label, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidatorInterface $validator) : Response
    {

        $input = array();
        $input['label_name'] = $request->get('label_name');
        $input['label_color'] = $request->get('label_color');
        $input['label_icon'] = $request->get('label_icon');
        $input['label_description'] = $request->get('label_description');

        // Persist new Label
        $label->setName($input['label_name']);
        $label->setColor($input['label_color']);
        $label->setIcon($input['label_icon']);
        $label->setDescription($input['label_description']);

        $violations = $validator->validate($label);

        dump($violations);
        if (empty($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validators', 'labels.store.violations', 'labels_add');
        }

        $entityManager->persist($label);
        $entityManager->flush();

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'labels.store.success', 'labels_add');
    }

    /**
     * Delete Label Action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/{id}/delete", name="labels_delete", methods={"POST"})
     */
    public function delete($id, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $label = $entityManager->getRepository(Label::class)->find($id);
        $entityManager->remove($label);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'labels.delete', 'labels_index');
    }
}
