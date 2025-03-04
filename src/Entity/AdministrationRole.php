<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Odiseo\SyliusRbacPlugin\Model\Permission;
use Odiseo\SyliusRbacPlugin\Model\PermissionInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class AdministrationRole implements AdministrationRoleInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected ?string $name = null;

    protected array $permissions = [];

    /**
     * @var Collection<int, AdministrationRoleAwareInterface>
     */
    protected Collection $adminUsers;

    public function __construct()
    {
        $this->adminUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAdminUsers(): Collection
    {
        return $this->adminUsers;
    }

    public function addAdminUser(AdministrationRoleAwareInterface $adminUser): void
    {
        if (!$this->adminUsers->contains($adminUser)) {
            $this->adminUsers->add($adminUser);
        }
    }

    public function removeAdminUser(AdministrationRoleAwareInterface $adminUser): void
    {
        if ($this->adminUsers->contains($adminUser)) {
            $this->adminUsers->removeElement($adminUser);
        }
    }

    public function hasAdminUser(AdministrationRoleAwareInterface $adminUser): bool
    {
        return $this->adminUsers->contains($adminUser);
    }

    public function addPermission(PermissionInterface $permission): void
    {
        if ($permission === null) {
            return;
        }

        try {
            $data = json_decode($permission->serialize(), true);
            $this->permissions[$permission->type()] = $data['operation_types'] ?? [
                OperationType::READ,
                OperationType::CREATE,
                OperationType::UPDATE,
                OperationType::DELETE
            ];
        } catch (\Exception $e) {
            // Skip invalid permissions
            return;
        }
    }

    public function removePermission(PermissionInterface $permission): void
    {
        unset($this->permissions[$permission->type()]);
    }

    public function clearPermissions(): void
    {
        $this->permissions = [];
    }

    public function hasPermission(PermissionInterface $permission): bool
    {
        try {
            if (!isset($this->permissions[$permission->type()])) {
                return false;
            }

            $storedPermission = $this->permissions[$permission->type()];
            if (is_array($storedPermission)) {
                return Permission::fromArray(['type' => $permission->type(), 'operation_types' => $storedPermission])->equals($permission);
            }
            
            return Permission::unserialize($storedPermission)->equals($permission);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPermissions(): array
    {
        $permissions = [];
        foreach ($this->permissions as $type => $permission) {
            try {
                if (is_array($permission)) {
                    $permissions[] = Permission::fromArray(['type' => $type, 'operation_types' => $permission]);
                } else {
                    $permissions[] = Permission::unserialize($permission);
                }
            } catch (\Exception $e) {
                // Skip invalid permissions
                continue;
            }
        }

        return $permissions;
    }
}
