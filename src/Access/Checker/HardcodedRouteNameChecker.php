<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Checker;

final class HardcodedRouteNameChecker implements RouteNameCheckerInterface
{
    public function isAdminRoute(string $routeName): bool
    {
        return
            str_contains($routeName, 'sylius_admin') ||
            str_contains($routeName, 'odiseo_sylius_rbac_plugin_admin') ||
            str_contains($routeName, 'open_marketplace_admin') ||
            str_contains($routeName, 'bitbag_sylius_cms_plugin_admin') ||
            str_contains($routeName, 'app');
        ;
    }
}
