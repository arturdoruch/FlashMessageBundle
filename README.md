FlashMessageBundle
================

FlashMessageBundle allows in very convenient way sets or adds flash messages.
It's helpful, when we want to give user some response information about controller action status.
Flash message manager working with `Symfony\Component\HttpFoundation\Session\Flash\FlashBag` and every setting message is automatically translated by `Symfony\Component\Translation\Translation`.

## Installation

Add bundle name for composer.json require block
```json
"require": {
    ...
    "arturdoruch/flash-message-bundle": "dev-master"
}
```

Install bundle by running command.
```sh
php composer.phar update arturdoruch/flash-message-bundle
```

Add bundle to your application kernel.
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new ArturDoruch\FlashMessageBundle\ArturDoruchFlashMessageBundle(),
    );
}
```

## Configuration
This bundle configured under the `artur_doruch_flash_message` key in your application configuration.

<a name="#classes"></a>
####<i>classes</i>

<b>type</b>: array <b>default</b>:
```
    success: success
    error: danger
    notice: warning
```

An array of key-value pairs, where key is a message type and value a css class name.
This parameter allows to define string that can be used in template as CSS class name for stylize displaying messages.

```yml
// app/config/config.yml
artur_doruch_flash_message:
    classes:
        error: fail
        notice: notice
        custom: custom-class-name
```

To use this parameter in template call `ad_flash_messages_class_name(type)` function with message type as parameter.
See `Resources/views/messages.html.twig` file.

## Controller

Every message setting by service `ad_flash_message` is automatically translated by `Symfony\Component\Translation\Translation`.

To get flash message service.

```php
public function indexAction()
{
    $flash = $this->get('ad_flash_message');
    ...
}
```

##### Set, add messages

By default flash messages are translated with "messages" domain.
Methods starting with name "add" or "set", adds or sets messages to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`, which next we can display in view template.

```php
/**
 * Translates custom type message and sets it into session flash bag. Overrides previous message if was set.
 *
 * @param string      $type        Can be any string describing action status. For types: "success", "error", "notice"
 *                                 use dedicated methods "setSuccess", "setError", "setNotice".
 *
 * @param string|null $message     Message text. Given text is always translated by "Symfony\Component\Translation\Translation"
 *                                 with "message" domain as default (of course if exists any translations for given string).
 *                                 If $message is null then will be dynamically creates as "translationId" based
 *                                 on called controller action name in convention: "company_bundle_controller.action.$type".
 *                                 For example, if we call this method (and $message is null) in controller
 *                                 "App\DemoBundle\Controller\ProductController::createAction"
 *                                 then will be generated this "translationId" value: "app_demo_project.create.$type".
 *
 * @param array       $parameters  Parameters for translation message.
 * @param string|null $domain      Translation domain. As default is "messages".
 */
public function set($type, $message = null, array $parameters = array(), $domain = null) {}

/**
 * Adds and translates custom type flash message.
 */
public function add($type, $message = null, array $parameters = array(), $domain = null) {}
```

Difference between methods "set" and "add" is obvious. "add" adds new message into flashBag array collection, while "set" override existing array messages collection by new one.

##### Get messages

Methods starting with name "get" only prepare message and returns it without adding to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`. 
It's useful if you want to return translated message. For example if you work with REST api or Ajax request.

```php
/**
 * Gets custom type translated flash message.
 * This method not adds message into session flash bug.
 * Just creates, translates and returns it.
 */
public function get($type, $message = null, array $parameters = array(), $domain = null) {}
```

Instead sets $type by hand you can use these convenient methods.

```php
/**
 * Available methods sets, adds or gets messages with concrete types: "Success", "Error", "Notice".
 */
public function setSuccess($message = null, array $parameters = array(), $domain = null) {}
public function setError($message = null, array $parameters = array(), $domain = null) {}
public function setNotice($message = null, array $parameters = array(), $domain = null) {}

public function addSuccess($message = null, array $parameters = array(), $domain = null) {}
public function adsError($message = null, array $parameters = array(), $domain = null) {}
public function addNotice($message = null, array $parameters = array(), $domain = null) {}

public function getSuccess($message = null, array $parameters = array(), $domain = null) {}
public function getError($message = null, array $parameters = array(), $domain = null) {}
public function getNotice($message = null, array $parameters = array(), $domain = null) {}
```

#### Add or get messages for CRUD actions.

FlashMessageBundle provides methods to straitforword setting flash messages, when we're doing repetitive CRUD operations.
Messages for CRUD action are translated with "crudMessages" domain.
See `Resources/translations/crudMessages.en.yml` file in this bundle.

```php
/**
 * Adds and translates flash message for CRUD action.
 * Generates "translationId" based on given parameters values and/or
 * dynamically generated based on $entity value and called controller name.
 * Generated "translationId" has format "crud.action.type".
 * All CRUD messages are translated with "crudMessages" domain.
 * See 'Resources/translations/crudMessages.en.yml'.
 *
 * @param string $type         Can be any string describing action status. For types: "success", "error", "notice"
 *                             use dedicated methods "addCrudSuccess", "addCrudError", "addCrudNotice".
 *
 * @param string $entity       Persistence entity object or entity name. Is used as parameter %entity%
 *                             in translation files.
 *                             For more clarify see "Resources/translations/crudMessages.en.yml" file.
 *
 * @param null|string $item    Single entity object name. Is used as parameter %item% in translation files.
 *                             For more clarify see "Resources/translations/crudMessages.en.yml" file.
 *
 *                             If $item is null and $entity is object then
 *                             will attempt to call methods getName() on $entity object "$entity->getName()".
 *                             If method exists then $item will be filled by the returned value.
 *
 * @param null|string $action  This parameter is used for generate "translationId".
 *                             If null then $action is generated based on called controller action name.
 *                             For example if called controller is     "App\DemoBundle\Controller\ProductController::createAction"
 *                             $action will be "create".
 */
public function addCrud($type, $entity, $item = null, $action = null) {}

/**
 * Gets custom type translated flash message for CRUD action.
 * This method not adds message into session flash bug.
 * Just creates, translates and returns it.
 */
public function getCrud($type, $entity, $item = null, $action = null) {}

/**
 * Other available methods adds or gets CRUD messages with concrete types: "Success", "Error", "Notice".
 */
public function addCrudSuccess($entity, $item = null, $action = null) {}
public function addCrudNotice($entity, $item = null, $action = null) {}
public function addCrudError($entity, $item = null, $action = null) {}

public function getCrudSuccess($entity, $item = null, $action = null) {}
public function getCrudNotice($entity, $item = null, $action = null) {}
public function getCrudError($entity, $item = null, $action = null) {}
```

### Example usage

```php
public function indexAction()
{
    ...
    $flash = $this->get('ad_flash_message');

    // Set flash messages
    $flash->setSuccess();
    $flash->setError();
    $flash->set('customType', 'Some flash message');

    // Add another message for "success" type array collection.
    $flash->addSuccess('Another success message');
}

public function sendEmailAction()
{
    ...
    $flash = $this->get('ad_flash_message');

    // Set flash messages with custom domain
    // Email was successfully send.
    $parameters = array(
        '%user%' => 'John Doe'
    );
    $flash->setSuccess(null, $parameters, 'emailMessages');
    
    // Failure email sending.
    $flash->setError(null, array(), 'emailMessages');

    // Get flash message
    return new Response($flash->getSuccess(null, $parameters, 'emailMessages'));
}
```

#####CRUD example.

```php
public function updateAction(Product $product, Request $request)
{
    ...
    $product->setName('Framework');
    
    // Create and valid form. If form is valid save entity and set flash message.
    
    $flash = $this->get('ad_flash_message');
    // Message will be "Product Framework has been updated."
    $flash->addCrudSuccess($product);
    
    // For this message in translation file "crudMessages" must be defined
    // new key 'crud.customaction.success'.
    $flash->addCrudSuccess('Product2', 'Bundle', 'customAction');
    // Add Crud message with custom type.
    $flash->add('customType', 'Product', 'Github');
    
    // Get message
    $updateSuccessMsg = $flash->getCrudSuccess($product);
}
```

##View

###Usage

For displaying flash messages just write this line of code into your base template file or wherever you want.
```twig
    {{ ad_flash_messages() }}
```

#####Optional
```twig
    {{ ad_flash_messages_class_name(type) }}
```
This function returns CSS class name related to given message type parameter.
Allows to customize displaying messages by CSS style.
See how <a href="#classes">configuration CSS classes names.</a>


Of course you can customize whole messages template by overriding `Resources/views/messages.html.twig` file.
To do this put template file into `app/Resources/ArturDoruchFlashMessageBundle/views/messages.html.twig` location in your Symfony app.

