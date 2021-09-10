<?php

namespace App\Controller;

use App\Entity\Purse;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $invitationCode = $request->query->get(User::INVITE);
        $form = $this->createForm(
            RegistrationFormType::class,
            $user,
            [
                'attr' => [
                    User::INVITE => $invitationCode
                ]
            ]
        );

        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->get('invite')->getData()) {
            $parent = $entityManager->getRepository(User::class)
                ->findOneBy(
                    [
                        'hash' => $form->get('invite')->getData()
                    ]
                );
            if (!$parent instanceof User) {
                $form->get('invite')
                    ->addError(new FormError('Wrong Invitation code'));
            }
        }

//        return $this->redirectToRoute('app_register', ['form' => $form]);
//        return new Response($form->getErrors());

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $hash = hash("crc32", $user->getUserIdentifier());
            $user->setHash($hash);


            $user->setParent($parent ?? null);

            $entityManager->persist($user);
            $entityManager->getRepository(Purse::class)
                ->createPursesForUser($user);

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
