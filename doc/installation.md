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

5. Include traits and override the models

```php
<?php
// src/Entity/User/AdminUser.php

// ...
use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareTrait;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
class AdminUser extends BaseAdminUser implements AdministrationRoleAwareInterface
{
    use AdministrationRoleAwareTrait;

    // ...
}
```

6. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```

7. Run installation command

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
