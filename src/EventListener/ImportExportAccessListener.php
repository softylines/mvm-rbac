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
        'sylius.tax_category' => 'tax_categories_management',
        'sylius.product_option' => 'options',
        'sylius.product_attribute' => 'attributes_management',
        'sylius.taxon' => 'taxons_management',
        'sylius.taxonomy' => 'taxons_management',
        'sylius.open_market_place.vendor' => 'vendor'
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
        if ($this->isSampleDownloadRoute($path)) {
            return;
        }
        if (!$this->isImportExportRoute($path)) {
            return;
        }

        $resource = $this->getResourceFromRequest($request);
        
        // Don't modify vendor resource prefix
        if ($resource === 'vendor') {
            $resource = 'open_market_place.vendor';
        } 
        // Add sylius prefix for other resources if needed
        elseif ($resource && !str_starts_with($resource, 'sylius.')) {
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
            throw new AccessDeniedHttpException(sprintf(
                'Access Denied: No permission to %s %s',
                $operationType->__toString(),
                $section->__toString()
            ));
        }

    }
    private function isSampleDownloadRoute(string $path): bool
    {
        return str_contains($path, '/import/sample/');
    }

    private function isImportExportRoute(string $path): bool
    {
        return str_contains($path, '/import/') || str_contains($path, '/export/');
    }

    private function getResourceFromRequest($request): ?string
    {
        if ($request->attributes->has('resource')) {
            $resource = $request->attributes->get('resource');
            
            if ($resource === 'open_marketplace.vendor') {
                return 'open_market_place.vendor';
            }
            
            if (!str_contains($resource, '.')) {
                $resource = 'sylius.' . $resource;
            }
            
            return $resource;
        }

        $pathParts = explode('/', trim($request->getPathInfo(), '/'));
        
        if (count($pathParts) >= 3 && $pathParts[0] === 'admin' && in_array($pathParts[1], ['import', 'export'])) {
            $resourceName = $pathParts[2];
            
            // Special handling for open marketplace vendor
            if ($resourceName === 'open_marketplace.vendor') {
                return 'open_market_place.vendor';
            }
            
            // Don't add sylius prefix if it's already a prefixed resource
            if (!str_contains($resourceName, '.')) {
                return 'sylius.' . $resourceName;
            }
            
            return $resourceName;
        }

        return null;
    }
} 