<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Repository\UserRepository;

class EmailController extends AbstractController
{

    /**
     * Verify User email
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EmailVerifier $emailVerifier
     * @return Response
     *
     * @Route("/verify/email", name="verify_email", methods={"GET"})
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository, EmailVerifier $emailVerifier, EventDispatcherInterface $eventDispatcher): Response
    {

        $id = $request->query->get('id');


        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('app_dashboard');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('app_dashboard');
      }


        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            //$emailVerifier->handleEmailConfirmation($request, $this->getUser());
            $emailVerifier->handleEmailConfirmation($request, $user, $eventDispatcher);

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
//            return $this->redirectToRoute('');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_dashboard');

    }



    /**
     * Activate User Action
     *
     * @param UserRepository $userRepository
     * @Route("/activate/{activation_hash}", name="app_activate_user")
     */
    public function activateUserEmail(Request $request, UserRepository $userRepository, EmailVerifier $emailVerifier, UserPasswordHasherInterface $passwordHasher)
    {

        $activationHash = $request->attributes->get('activation_hash');

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        $user = $userRepository->findOneBy(['activationHash' => $activationHash]);


        // Deny access for already activated users
        if ($user->getIsActivated()) {
            throw $this->createAccessDeniedException();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $plainPassword
            );

            $user->setPassword($encodedPassword);

            // Activate email confirmation link, sets User::isActivatedtrue and persists
            try {
                $emailVerifier->handleEmailConfirmation($request, $user);

            } catch (VerifyEmailExceptionInterface $exception) {
                $this->addFlash('activation_email_error', $exception->getReason());
//            return $this->redirectToRoute('');
            }

            $user->setActivationHash(NULL);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            //$this->addFlash('success',);
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/user/activation/activate.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);


    }


}