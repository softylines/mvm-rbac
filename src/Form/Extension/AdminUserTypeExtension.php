<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Form\Extension;

use Odiseo\SyliusRbacPlugin\Entity\AdministrationRole;
use Sylius\Bundle\CoreBundle\Form\Type\User\AdminUserType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

final class AdminUserTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('administrationRoles', EntityType::class, [
            'class' => AdministrationRole::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ar')
                    ->orderBy('ar.name', 'ASC');
            },
            'label' => 'odiseo_sylius_rbac_plugin.ui.administration_roles',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [AdminUserType::class];
    }
}
