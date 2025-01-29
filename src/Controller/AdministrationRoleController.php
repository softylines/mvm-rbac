<?php

namespace Odiseo\SyliusRbacPlugin\Controller;

use Odiseo\SyliusRbacPlugin\Normalizer\AdministrationRolePermissionNormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrationRoleController extends AbstractController
{
    public function createAction(Request $request): Response
    {
        return $this->render('@OdiseoSyliusRbacPlugin/Admin/AdministrationRole/create.html.twig', [
            'permissions' => $this->getPermissionsList(),
            'importable_resources' => [
                'country',
                'customer_group',
                'payment_method',
                'tax_category',
                'customer',
                'product'
            ],
            'exportable_resources' => [
                'country',
                'order',
                'customer',
                'product'
            ]
        ]);
    }
} 