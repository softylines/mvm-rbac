<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Odiseo\SyliusRbacPlugin\Model\PermissionInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AdministrationRoleInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function addPermission(PermissionInterface $permission): void;

    public function removePermission(PermissionInterface $permission): void;

    public function clearPermissions(): void;

    public function hasPermission(PermissionInterface $permission): bool;

    public function getPermissions(): array;

    /**
     * @return Collection<int, AdminUserInterface>
     */
    public function getAdminUsers(): Collection;

    public function addAdminUser(AdminUserInterface $adminUser): void;

    public function removeAdminUser(AdminUserInterface $adminUser): void;

    public function hasAdminUser(AdminUserInterface $adminUser): bool;
}
