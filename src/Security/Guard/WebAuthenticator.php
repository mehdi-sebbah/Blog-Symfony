<?php

namespace App\Security\Guard;

use App\Form\LoginType;
use App\DataTransferObject\Credentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class WebAuthenticator extends AbstractFormLoginAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;

    private FormFactoryInterface $formFactory;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        UrlGeneratorInterface $urlGenerator, 
        FormFactoryInterface $formFactory, 
        UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * Récupère le chemin de la page de connexion
     *
     * @return void
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(("security_login"));
    }

    /**
     * Vérifie si la requête et une requête que l'ont doit traiter comme une autentification(Que c'est une méthode POST et que je suis bien sur la page de login)
     * @param Request $request
     * @return void
     */
    public function supports(Request $request)
    {
        return $request->isMethod(Request::METHOD_POST)
            && $request->attributes->get("_route") === "security_login";
    }

    /**
     * Récupère les valeurs de notre formulaire et vérifie si notre formulaire qui a était rempli est bien rempli, si c'est le cas il retourne les credentials.
     * @param Request $request
     * @return void
     */
    public function getCredentials(Request $request)
    {
        $credentials =new Credentials("");
        $form = $this->formFactory->create(LoginType::class, $credentials)->handleRequest($request);

        if(!$form->isValid()){
            return null;
        }

        return $credentials;
    }

    /**
     *Récupère l'utilisateur en fonction de son username (Dans notre cas c'est un email)
     * @param [type] $credentials
     * @param UserProviderInterface $userProvider
     * @return void
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }


    /**
     *Vérifie si le mot de passe saisie est le bon.
     * @param [type] $credentials
     * @param UserInterface $user
     * @return void
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if($valid = $this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword())){
            return true;
        }

        throw new AuthenticationException('Password not valid');
    }

    /**
     *Si tout est bon on fait une redirection vers la page d'accueil.
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return void
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate(("index")));
    }
}