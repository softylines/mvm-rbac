<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait AdministrationRoleAwareTrait
{
    /**
     * @var Collection<int, AdministrationRoleInterface>
     */
    protected Collection $administrationRoles;

    public function __construct()
    {
        $this->administrationRoles = new ArrayCollection();
    }

    public function getAdministrationRoles(): Collection
    {
        return $this->administrationRoles;
    }

    public function addAdministrationRole(AdministrationRoleInterface $role): void
    {
        if (!$this->administrationRoles->contains($role)) {
            $this->administrationRoles->add($role);
        }
    }

    public function removeAdministrationRole(AdministrationRoleInterface $role): void
    {
        if ($this->administrationRoles->contains($role)) {
            $this->administrationRoles->removeElement($role);
        }
    }

    // For backwards compatibility
    public function getAdministrationRole(): ?AdministrationRoleInterface
    {
        return $this->administrationRoles->first() ?: null;
    }

    public function setAdministrationRole(?AdministrationRoleInterface $role): void
    {
        $this->administrationRoles->clear();
        if ($role !== null) {
            $this->addAdministrationRole($role);
        }
    }
}
