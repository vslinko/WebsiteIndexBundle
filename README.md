# RithisWebsiteIndexBundle

Symfony2 Bundle which compile all routes on single page.

## Installation

Run this command in your project directory:

``` bash
$ composer.phar require rithis/website-index-bundle:@dev
```

After that enable bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Rithis\WebsiteIndexBundle\RithisWebsiteIndexBundle(),
    );
}
```

## Usage

### Routing

First of all include bundle routing:

``` yaml
# app/routing.yml

_rithis_website_index:
    resource: "@RithisWebsiteIndexBundle/Resources/config/routing.yml"
```

If you want to make redirect from main page then add one more route:

``` yaml
# app/routing.yml

_index:
    pattern: /
    defaults: { _controller: FrameworkBundle:Redirect:redirect, route: rithis_website_index_website_index_get }
```

### Routes with parameters

By default only routes without parameters compiled. If you want to add routes with parameters you can configure bundle:

``` yaml
# app/config.yml

rithis_website_index:
    rithis_news_news_get: RithisNewsBundle:News
```

Where key (ex. rithis_news_news_get) is route name and value (ex. RithisNewsBundle:News) is entity.
