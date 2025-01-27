<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class AccessDeniedException extends AccessDeniedHttpException
{
    public static function forAdministrator(): self
    {
        return new self('Access denied. You do not have permission to access this resource.');
    }
} 