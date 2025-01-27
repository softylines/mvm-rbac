<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

final class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        return new Response(
            $this->twig->render('@OdiseoSyliusRbacPlugin/Admin/Error/403.html.twig', [
                'message' => 'odiseo_sylius_rbac_plugin.you_have_no_access_to_this_section'
            ]),
            Response::HTTP_FORBIDDEN
        );
    }
} 