# Puzzle User
**=========================================**

Puzzle admin dashboard


### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

`composer require webundle/puzzle-admin-dashboard`

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Puzzle\Admin\AdminBundle\PuzzleAdminBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Register the Routes

Load the bundle's routing definition in the application (usually in the `app/config/routing.yml` file):

# app/config/routing.yml
```yaml
puzzle_admin:
        resource: "@PuzzleAdminBundle/Resources/config/routing.yml"
```

### Step 4: Configure Puzzle OAuth options and navigation menu

Then, enable management bundle via admin modules interface by adding it to the list of registered bundles in the `app/config/config.yml` file of your project under:

```yaml
puzzle_admin:
    template_bundle:    '@PuzzleAdmin'
    website:
        name:           'Puzzle Client Admin'
        description:    'Puzzle Client Admin Description'
        email:          'johndoe@exemple.com'
        time_format:    'H:m:i'
        date_format:    'd-m-Y'
    navigation:
        nodes:
            dashboard:
                label: 'admin.navigation.dashboard.title'
                translation_domain: 'admin'
                path: admin_homepage
                attr:
                    class: 'fa fa-home'
                parent: ~
                user_roles: ['ROLE_ADMIN']
                tooltip: 'admin.navigation.dashboard.tooltip'
```

