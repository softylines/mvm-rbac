## Installation

1. Run `composer require softylines/mvm-rbac`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
 Odiseo\SyliusRbacPlugin\OdiseoSyliusRbacPlugin::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
  # ...
  - { resource: '@OdiseoSyliusRbacPlugin/Resources/config/config.yaml' }
```

4. Add the admin route

```yml
# config/routes.yaml
odiseo_sylius_rbac_plugin_admin:
  resource: '@OdiseoSyliusRbacPlugin/Resources/config/routing/admin.yaml'
  prefix: /admin
```
5.add config/packages/sylius_user.yaml
```yaml
sylius_user:
    resources:
        admin:
            user:
                classes:
                    model: App\Entity\User\AdminUser

```
6.update config/packages/doctrine.yaml
```yaml
parameters:
  # Adds a fallback DATABASE_URL if the env var is not set.
  # This allows you to run cache:warmup even if your
  # environment variables are not available yet.
  # You should not need to change this value.
  env(DATABASE_URL): ''

doctrine:
  dbal:
    driver: 'pdo_mysql'
    server_version: '8.0'
    charset: UTF8
    url: '%env(resolve:DATABASE_URL)%'
    types:
      uuid: 'Ramsey\Uuid\Doctrine\UuidType'
  orm:
    auto_generate_proxy_classes: '%kernel.debug%'
    naming_strategy: doctrine.orm.naming_strategy.underscore
    auto_mapping: true
    mappings:
      App:
        is_bundle: false
        type: annotation
        dir: '%kernel.project_dir%/src/Entity'
        prefix: 'App\Entity'
        alias: App
      MessagingComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/Messaging/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\Messaging\Entity'
      OrderComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/Order/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\Order\Entity'
      ProductComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/Product/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\Product\Entity'
      ProductListingComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/ProductListing/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\ProductListing\Entity'
      VendorComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/Vendor/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\Vendor\Entity'
      SettlementComponent:
        is_bundle: false
        type: xml
        dir: '%kernel.project_dir%/src/Component/Settlement/Resources/doctrine'
        prefix: 'BitBag\OpenMarketplace\Component\Settlement\Entity'


```
7. Include traits and override the models

```php
/src/Entity/User/AdminUser.php
<?php

declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
class AdminUser extends BaseAdminUser implements AdministrationRoleAwareInterface
{
    /** @var Collection<int, AdministrationRoleInterface> */
    protected Collection $administrationRoles;

    public function __construct()
    {
        parent::__construct();
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
            $role->addAdminUser($this);
        }
    }

    public function removeAdministrationRole(AdministrationRoleInterface $role): void
    {
        if ($this->administrationRoles->contains($role)) {
            $this->administrationRoles->removeElement($role);
            $role->removeAdminUser($this);
        }
    }

    public function hasAdministrationRole(AdministrationRoleInterface $role): bool
    {
        return $this->administrationRoles->contains($role);
    }

    public function getAdministrationRole(): ?AdministrationRoleInterface
    {
        return $this->administrationRoles->first() ?: null;
    }

    public function setAdministrationRole(?AdministrationRoleInterface $role): void
    {
        $this->administrationRoles->clear();
        if (null !== $role) {
            $this->addAdministrationRole($role);
        }
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        
        foreach ($this->getAdministrationRoles() as $administrationRole) {
            $permissions = $administrationRole->getPermissions();
            foreach ($permissions as $permission) {
                $reflection = new \ReflectionClass($permission);
                $properties = $reflection->getProperties();
                
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $value = $property->getValue($permission);
                    
                    if (is_string($value)) {
                        $roles[] = 'ROLE_' . strtoupper($value);
                        break;
                    }
                }
            }
            $roles[] = 'ROLE_' . strtoupper($administrationRole->getName());
        }
        
        return array_unique($roles);
    }
}




```

8. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```

9. Run installation command

   ```
   php bin/console odiseo:rbac:install
   ```

   Which consists of:

   - `sylius:fixtures:load`

     Loading fixture with a default "No sections access" role.

     The command runs in non-interactive mode so it will NOT purge your database.
     However, once you run it again it will throw an exception because of duplicate entry constraint violation.

     If you want to install RBAC plugin again on the same environment you will have to remove all roles manually
     via administration panel or run all commands except `sylius:fixtures:load` separately.
