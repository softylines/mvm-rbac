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

final class ExportActionVisibilityListener
{
    private const ROUTE_MAP = [
        'sylius_admin_customer_index' => 'customers',
        'sylius_admin_product_index' => 'products_management',
        'sylius_admin_order_index' => 'orders_management',
        'sylius_admin_country_index' => 'countries_management',
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
                if ($actionGroup->hasAction('export')) {
                    $actionGroup->removeAction('export');
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
            OperationType::export()
        );

        if ($this->administratorAccessChecker->canAccessSection($admin, $accessRequest)) {
            $exportAction = new Action();
            $exportAction->setName('export');
            $exportAction->setType('export');
            $exportAction->setLabel('sylius.ui.export');
            $exportAction->setOptions([
                'class' => 'blue',
                'icon' => 'upload',
                'header' => ['icon' => 'upload', 'label' => 'sylius.ui.export'],
            ]);
            
            $grid->getActionGroup('actions')->addAction($exportAction);
        }
    }
} 