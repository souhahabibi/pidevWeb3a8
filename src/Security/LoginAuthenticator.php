<?php
namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
use TargetPathTrait;
    public const LOGIN_ROUTE = 'app_login';

private UrlGeneratorInterface $urlGenerator;
private UserRepository $userRepository;

public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository , Security $security)
{
$this->urlGenerator = $urlGenerator;
$this->userRepository = $userRepository;
}

public function authenticate(Request $request): Passport
{
$email = $request->request->get('email', '');
$password = $request->request->get('password', '');

$user = $this->userRepository->findOneBy(['email' => $email]);

if (!$user) {
throw new CustomUserMessageAuthenticationException('Invalid credentials.');
}

// Validate password using your preferred method, for example:

return new Passport(
new UserBadge($email),
new PasswordCredentials($password),
[
new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
new RememberMeBadge(),
]
);
}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//{
//if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
//return new RedirectResponse($targetPath);
//}
//
//return new RedirectResponse($this->urlGenerator->generate('app_user_index'));
//}
{
    $user = $token->getUser();

        // Get the user's roles
        $roles = $user->getRoles();

        // Redirect based on the user's roles
        if (in_array('admin', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_index'));
        } elseif (in_array('coach', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_coach_index'));
        } elseif (in_array('client', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_client_index'));
        }

        // Default redirect if no matching role is found
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

protected function getLoginUrl(Request $request): string
{
return $this->urlGenerator->generate(self::LOGIN_ROUTE);
}
}
