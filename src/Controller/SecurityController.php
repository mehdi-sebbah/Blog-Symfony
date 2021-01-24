<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormError;
use App\DataTransferObject\Credentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     *
     * @return Response
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Le premier parametre dans encodePassword correspond a la classe qu'on a mis dans le security.yaml pour l'encodage ! il fera le lien entre le bonne algorithme et l'entiter user !
            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("security_login");
        };


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     *@Route("/login", name="security_login")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));

        
        if (null !== $authenticationUtils->getLastAuthenticationError(false)) {
            $form->addError(new FormError($authenticationUtils->getLastAuthenticationError()->getMessage())
            );
        }

        return $this->render("security/login.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
    }
}
