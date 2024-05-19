<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UsuarioRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPoint;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPointTrait;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPointTraitInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    private UsuarioRepository $userRepository;
    private RouterInterface $router;

    public const LOGIN_ROUTE = 'app_login';
    

    public function __construct(private UrlGeneratorInterface $urlGenerator, UsuarioRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {   
        
        $email = $request->request->getString('email');
        dd($email);
        $password = $request->request->getString('password');
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        $user = $this->userRepository->findByEmail($email);
        if ($user->isVerified() === false) {
            throw new AuthenticationException('Usuario no verificado');
        }
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        return new RedirectResponse(
            $this->router->generate('app_home_page')
        );
    }
    

    protected function getLoginUrl(Request $request): string
    {   
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

}
