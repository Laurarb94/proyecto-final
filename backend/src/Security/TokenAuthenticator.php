<?php

namespace App\Security;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class TokenAuthenticator extends AbstractAuthenticator
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /*Método que sirve para determinar si este autenticador se debe ejecutar para esta petición */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    /*Este método extrae el token del header, lo valida y crea el passport con la identidad del usuario si todo es correcto*/
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new CustomUserMessageAuthenticationException('No token provided');
        }

        $token = substr($authHeader, 7);

        error_log("Token recibido: $token");

        try {
            $user = $this->authService->getUserFromToken($token);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Token inválido');
        }

        if (!$user instanceof UserInterface) {
            throw new CustomUserMessageAuthenticationException('Usuario no encontrado');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), function() use ($user) {
            return $user;
        }));
    }

    /*Este método se llama si la autenticación ha sido exitosa, para las APIS devuelve null y la ejecución de la petición sigue */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /*Se llama si hubo algún fallo en la autenticación (token inválido, expirdado, usuario no encontardo...) */

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse('No autorizado', 401);
    }
}
