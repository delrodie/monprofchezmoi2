<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/change-password")
 */
class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/", name="app_change_password", methods={"GET","POST"})
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser(); //dd($user);

            $password = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Mot de passe changé avec succès");

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('change_password/i
        ndex.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
