<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\EmailVerifier;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Sodium\add;


/**
 * @Route("/users")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends BaseController
{


    /**
     * Index User Action
     *
     * @param UserRepository $userRepository
     * @return Response
     *
     * @Route("", name="users_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $data = array();
        $data['controller_name'] = 'UserController';
        $data['users'] = $userRepository->findAll();

        return $this->render('dashboard/users/index.html.twig', $data);
    }


    /**
     * Search User Action
     *
     * @param Request $request
     *
     *
     * @Route("/search", name="users_search", methods={"GET", "POST"})
     */
    public function search(Request $request, UserRepository $userRepository)
    {
        $input =  $request->get('search_form');

        $users = $userRepository->findBySearchQuery($input);

        return $this->render('dashboard/users/search.html.twig', ['users' => $users]);

    }

    /**
     * @return Response
     *
     * @Route("/add", name="users_add", methods={"GET", "POST"})
     *
     *
     */
    public function add()
    {
        $data = array();
        $data['controller_name'] = 'UserController';
        $data['user_roles'] = User::ROLES;

        return $this->render('dashboard/users/add.html.twig', $data);
    }

    /**
     * Details User Action
     *
     * @param User $user
     * @return Response
     *
     * @Route("/{id}/details", name="users_details", methods={"GET"})
     */
    public function details(User $user): Response
    {
        return $this->render('dashboard/users/details.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * Store User Action
     *
     * @param Request $request
     *
     * @Route("/store", name="users_store", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function store(Request $request, TranslatorInterface $translator, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier) : Response
    {

        $input = array();
        $input['user_email'] = $request->get('user_email');
        $input['user_roles'] = $request->get('user_roles');

        // Validator Rules
        // Email Constraints
        $emailConstraintNotBlank = new Assert\NotBlank();
        $emailConstraintNotBlank->message = $translator->trans('users.store.email.blank', array(), 'validation');
        $emailConstraintEmail = new Assert\Email();
        $emailConstraintEmail->message = $translator->trans('users.store.email.email', array(), 'validation');

        //Roles Constraints
        $rolesConstraintNotNull = new Assert\NotNull();
        $rolesConstraintNotNull->message = $translator->trans('users.store.roles.null', array(), 'validation');

        // Validator Rules Collection
        $constraints = new Assert\Collection([
            'user_email' => [
                $emailConstraintNotBlank,
                $emailConstraintEmail
            ],

            'user_roles' => [
                $rolesConstraintNotNull

            ]

        ]);

        // Validate input
        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $constraints);

        if (0 !== count($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;

            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validation', 'users.store.violations', 'users_index');
        }

        $validated = [];
        $validated['user_email'] = $input['user_email'];
        $validated['user_roles'] = $input['user_roles'];

        // Check if User already exist
        $check = $this->getDoctrine()->getRepository('App:User')->findOneBy(['email' => $validated['user_email']]);

        if (!$check == NULL) {
            return $this->message('danger_message', 'User  already exist!', '/users/add');
        }

        // Persist new User
        $activationHash = string_hasher(random_string());
        $user = new User();
        $user->setEmail($validated['user_email']);
        $user->setRoles($validated['user_roles']);
        $user->setActivationHash($activationHash);
        $entityManager->persist($user);
        $entityManager->flush();

        // Generate a signed url and email it to the user
        $emailVerifier->sendEmailActivation('app_activate_user', $user,
            (new TemplatedEmail())
                ->from(new Address('security@test.test', 'Security'))
                ->to($user->getEmail())
                ->subject('Activation E-Mail')
                ->htmlTemplate('email/activation-email.html.twig')
        );

        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'user.store.success', 'users_index');

    }

    /**
     * Show User Action
     *
     * @param Request $request
     * @param User $user
     * @return Response
     *
     * @Route("/{id}/show", name="users_show", methods={"GET", "POST"})
     */
    public function show($id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $data = array();
        $data['controller_name'] = 'UserController';
        $data['user'] = $user;
        $data['user_roles'] = User::ROLES;

        return $this->render('dashboard/users/show.html.twig', $data);
    }



    /**
     * Edit User Action
     *
     * @param Request $request
     * @param User $user
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     *
     *@Route("/{id}/edit", name="users_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $input = array();
        $input['user_email'] = $request->get('user_email');
        $input['user_roles'] = $request->get('user_roles');

        // Validator Rules
        // Email Constraints
        $emailConstraintNotBlank = new Assert\NotBlank();
        $emailConstraintNotBlank->message = $translator->trans('users.store.email.blank', array(), 'validation');
        $emailConstraintEmail = new Assert\Email();
        $emailConstraintEmail->message = $translator->trans('users.store.email.email', array(), 'validation');

        //Roles Constraints
        $rolesConstraintNotNull = new Assert\NotNull();
        $rolesConstraintNotNull->message = $translator->trans('users.store.roles.null', array(), 'validation');

        // Validator Rules Collection
        $constraints = new Assert\Collection([
            'user_email' => [
                $emailConstraintNotBlank,
                $emailConstraintEmail
            ],

            'user_roles' => [
                $rolesConstraintNotNull

            ]

        ]);

        // Validate input
        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $constraints);

        if (0 !== count($violations)) {

            $violationsMessages = [];

            foreach ($violations as $violation) {
                $message = $violation->getMessage();
                $violationsMessages[] = $message;
            }

            $this->get('session')->getFlashBag()->set('violations_messages',$violationsMessages);
            $this->get('session')->getFlashBag()->set('violations', true);

            return $this->transmessage($translator, 'warning_message', array(), 'validation', 'users.store.violations', 'users_index');
        }

        $validated = [];
        $validated['user_email'] = $input['user_email'];
        $validated['user_roles'] = $input['user_roles'];


        // Persist edited user
        $user->setEmail($validated['user_email']);
        $user->setRoles($validated['user_roles']);
        $entityManager->persist($user);
        $entityManager->flush();


        $this->get('session')->clear();
        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'users.edit.success', 'users_index');
    }


    /**
     * Delete User Action
     *
     * @param Request $request
     * @param User $user
     * @return Response
     *
     * @Route("/{id}/delete", name="users_delete", methods={"POST"})
     */
    public function delete($id, TranslatorInterface $translator, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return  $this->transmessage($translator,'success_message', array(), 'dashboard', 'users.delete.success', 'users_index');
    }

}