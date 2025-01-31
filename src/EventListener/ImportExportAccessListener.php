<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\EventListener;

use Odiseo\SyliusRbacPlugin\Access\Checker\AdministratorAccessCheckerInterface;
use Odiseo\SyliusRbacPlugin\Access\Model\AccessRequest;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Access\Model\Section;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\AccessListener;

final class ImportExportAccessListener
{
    private const RESOURCE_TO_SECTION_MAP = [
        'sylius.country' => 'countries_management',
        'sylius.order' => 'orders_management',
        'sylius.customer' => 'customers',
        'sylius.product' => 'products_management',
        'sylius.payment_method' => 'payment_methods_management',
        'sylius.tax_category' => 'tax_categories_management'
    ];

    public function __construct(
        private AdministratorAccessCheckerInterface $adminAccessChecker,
        private Security $security,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        if (!$this->isImportExportRoute($path)) {
            return;
        }

        $resource = $this->getResourceFromRequest($request);
        if ($resource && !str_starts_with($resource, 'sylius.')) {
            $resource = 'sylius.' . $resource;
        }
        

        if (!$resource) {
            throw new AccessDeniedHttpException(sprintf(
                'Invalid resource type: %s. Available resources: %s',
                $request->getPathInfo(),
                implode(', ', array_keys(self::RESOURCE_TO_SECTION_MAP))
            ));
        }

        if (!isset(self::RESOURCE_TO_SECTION_MAP[$resource])) {
            throw new AccessDeniedHttpException(sprintf(
                'Resource type not allowed: %s. Available resources: %s',
                $resource,
                implode(', ', array_keys(self::RESOURCE_TO_SECTION_MAP))
            ));
        }

        $token = $this->tokenStorage->getToken();
        $currentUser = $token ? $token->getUser() : null;
        

        if (!$currentUser instanceof AdminUserInterface) {
            throw new AccessDeniedHttpException('Access Denied: Not authenticated as admin');
        }

        $userRoles = $currentUser->getAdministrationRoles();

        $isImport = str_contains($path, '/import/');
        $operationType = $isImport ? OperationType::import() : OperationType::export();
        $section = Section::ofType(self::RESOURCE_TO_SECTION_MAP[$resource]);
        $accessRequest = new AccessRequest($section, $operationType);
        


        if (!$this->adminAccessChecker->canAccessSection($currentUser, $accessRequest)) {
            dump('Access Denied');
            throw new AccessDeniedHttpException(sprintf(
                'Access Denied: No permission to %s %s',
                $operationType->__toString(),
                $section->__toString()
            ));
        }

    }

    private function isImportExportRoute(string $path): bool
    {
        return str_contains($path, '/import/') || str_contains($path, '/export/');
    }

    private function getResourceFromRequest($request): ?string
    {
        if ($request->attributes->has('resource')) {
            $resource = $request->attributes->get('resource');
            dump('Resource from attributes:', $resource);
            if (!str_starts_with($resource, 'sylius.')) {
                $resource = 'sylius.' . $resource;
            }
            return $resource;
        }

        $pathParts = explode('/', trim($request->getPathInfo(), '/'));
        dump('Path parts:', $pathParts);
        
        if (count($pathParts) >= 3 && $pathParts[0] === 'admin' && in_array($pathParts[1], ['import', 'export'])) {
            $resourceName = $pathParts[2];
            $finalResource = !str_starts_with($resourceName, 'sylius.') 
                ? 'sylius.' . $resourceName 
                : $resourceName;
            
            dump([
                'Original resource name' => $resourceName,
                'Final resource name' => $finalResource,
                'Has sylius. prefix?' => str_starts_with($finalResource, 'sylius.'),
                'Available mappings' => array_keys(self::RESOURCE_TO_SECTION_MAP),
                'Is in mappings?' => isset(self::RESOURCE_TO_SECTION_MAP[$finalResource])
            ]);
            
            return $finalResource;
        }

        dump('No valid resource found in request');
        return null;
    }
} 