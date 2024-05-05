<h1>Multi factor authentication plugin</h1>

<p>
    Multi factor authentication plugin for ShopUser and AdminUser
</p>


## Installation

Require plugin with composer :

```bash
composer require abenmada/multi-factor-authentication
```

⚠️  Please delete the automatically generated files **config/packages/scheb_2fa.yaml** and **config/routes/scheb_2fa.yaml**.

Change your `config/bundles.php` file to add the line for the plugin :

```php
<?php

return [
    //..
    Abenmada\MultiFactorAuthenticationPlugin\MultiFactorAuthenticationPlugin::class => ['all' => true],
    Scheb\TwoFactorBundle\SchebTwoFactorBundle::class => ['all' => true],
];
```

Then create the config file in `config/packages/abenmada_multi_factor_authentication_plugin.yaml` :

```yaml
imports:
    - { resource: "@MultiFactorAuthenticationPlugin/Resources/config/services.yaml" }
```

Then import the routes in `config/routes/abenmada_multi_factor_authentication_plugin.yaml` :

```yaml
abenmada_multi_factor_authentication_plugin_shop_routing:
    resource: "@MultiFactorAuthenticationPlugin/Resources/config/routes/sylius_shop.yaml"
    prefix: /{_locale}

abenmada_multi_factor_authentication_plugin_admin_routing:
    resource: "@MultiFactorAuthenticationPlugin/Resources/config/routes/sylius_admin.yaml"
    prefix: /%sylius_admin.path_name%
```

Change your `config/services.yaml` file :

```yaml
parameters:
    abenmada_multi_factor_authentication_plugin_issuer: "Fashion Web Store" # Issuer name used in QR code
```

Change your `config/packages/security.yaml` file :

```yaml
security:
    firewalls:
        admin:
            two_factor:
                auth_form_path: abenmada_multi_factor_authentication_plugin_admin_user_login
                check_path: abenmada_multi_factor_authentication_plugin_admin_user_login_check

        shop:
            two_factor:
                auth_form_path: abenmada_multi_factor_authentication_plugin_shop_user_login
                check_path: abenmada_multi_factor_authentication_plugin_shop_user_login_check

    access_control:
        # This makes the logout route accessible during two-factor authentication. Allows the user to cancel two-factor authentication, if they need to.
        - { path: ^/logout, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # This ensures that the form can only be accessed when two-factor authentication is in progress.
        - { path: ^/2fa, role: IS_AUTHENTICATED_2FA_IN_PROGRESS }
```

Add a new tab in `templates/bundles/SyliusAdminBundle/Layout/_security.html.twig` file **(if it doesn't exist, customize it)** :

```html
<a href="{{ path('abenmada_multi_factor_authentication_plugin_admin_user_enable', {'id': app.user.id}) }}" class="item">
    <i class="shield icon"></i>
    {{ 'abenmada_multi_factor_authentication_plugin.ui.multi_factor_authentication'|trans }}
</a>
```

Customize the account menu : 

```php
<?php

declare(strict_types=1);

namespace App\Menu\Listener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AccountMenuListener
{
    public function invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('multiFactorAuthentication', ['route' => 'sylius_shop_account_abenmada_multi_factor_authentication_plugin_shop_user_enable'])
            ->setLabel('abenmada_multi_factor_authentication_plugin.ui.multi_factor_authentication')
            ->setLabelAttribute('icon', 'shield');
    }
}
```

```yaml
services:
    app.listener.account_menu:
        class: App\Menu\Listener\AccountMenuListener
        tags:
            - { name: kernel.event_listener, event: sylius.menu.shop.account, method: invoke }
```

Update the entity `src/Entity/User/AdminUser.php` :

```php
<?php

declare(strict_types=1);

namespace App\Entity\User;

use Abenmada\MultiFactorAuthenticationPlugin\Model\MultiFactorAuthenticationTrait;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_admin_user')]
class AdminUser extends BaseAdminUser implements TwoFactorInterface
{
    use MultiFactorAuthenticationTrait;

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->getEmail() ?: '';
    }
}
```

Update the entity `src/Entity/User/ShopUser.php` :

```php
<?php

declare(strict_types=1);

namespace App\Entity\User;

use Abenmada\MultiFactorAuthenticationPlugin\Model\MultiFactorAuthenticationTrait;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Sylius\Component\Core\Model\ShopUser as BaseShopUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shop_user")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_shop_user')]
class ShopUser extends BaseShopUser implements TwoFactorInterface
{
    use MultiFactorAuthenticationTrait;

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->getEmail() ?: '';
    }
}
```

Run the migration :

 ```bash
bin/console doctrine:migrations:migrate
```

Install the assets :

```bash
bin/console assets:install --ansi
```
