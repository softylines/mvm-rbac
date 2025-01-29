<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Grid;

use Odiseo\SyliusRbacPlugin\Access\Checker\AdministratorAccessCheckerInterface;
use Odiseo\SyliusRbacPlugin\Access\Model\AccessRequest;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Access\Model\Section;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Event\GridDefinitionConverterEvent;
use Symfony\Component\Security\Core\Security;

final class ImportActionVisibilityListener
{
    private const ROUTE_MAP = [
        'sylius_admin_customer_index' => 'customers',
        'sylius_admin_product_index' => 'products_management',
        'sylius_admin_country_index' => 'countries_management',
        'sylius_admin_payment_method_index' => 'payment_methods_management',
        'sylius_admin_tax_category_index' => 'tax_categories_management',
    ];

    public function __construct(
        private AdministratorAccessCheckerInterface $administratorAccessChecker,
        private Security $security
    ) {
    }

    public function onSyliusGridAdmin(GridDefinitionConverterEvent $event): void
    {
        $grid = $event->getGrid();
        
        $actions = $grid->getActions();
        
        foreach (['actions', 'bulkActions'] as $actionType) {
            if ($grid->hasActionGroup($actionType)) {
                $actionGroup = $grid->getActionGroup($actionType);
                if ($actionGroup->hasAction('import')) {
                    $actionGroup->removeAction('import');
                }
            }
        }

        $request = $event->getGrid()->getParameters()->get('request');
        if (null === $request) {
            return;
        }

        $route = $request->attributes->get('_route');
        $section = self::ROUTE_MAP[$route] ?? null;
        
        if (null === $section) {
            return;
        }

        $admin = $this->security->getUser();
        $accessRequest = new AccessRequest(
            Section::ofType($section),
            OperationType::import()
        );

        if ($this->administratorAccessChecker->canAccessSection($admin, $accessRequest)) {
            $importAction = new Action();
            $importAction->setName('import');
            $importAction->setType('import');
            $importAction->setLabel('sylius.ui.import');
            $importAction->setOptions([
                'class' => 'blue',
                'icon' => 'download',
                'header' => ['icon' => 'download', 'label' => 'sylius.ui.import'],
            ]);
            
            $grid->getActionGroup('actions')->addAction($importAction);
        }
    }
} 