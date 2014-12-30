FlashMessageBundle
================

Flash message manager working with Symfony session flashBag.
Allows in very convenient way add or set flash messages and display them in a view template.

<!--Features:
Add or set messages
add or set crud operations messages
Get - sets and return translated message
Get crud - sets and return translated message without them into flashbag.-->


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

Add ArturDoruchFlashMessageBundle to your application kernel.
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

####<i>classes</i>

<b>type</b>: array <b>default</b>:
```
    success: success
    error: danger
    notice: warning
```

An array of key-value pairs, where key is a message type and value a css class name.
This parameter allows to define string that can be used in template as CSS class name for stylize displaying messages.
See example.

```yml
// app/config/config.yml
artur_doruch_flash_message:
    classes:
        error: fail
        notice: notice
        custom: custom-class-name
```

To use this parameter in template call "ad_flash_messages_class_name" function with message type as parameter.
See "Resources/views/messages.html.twig" file.


## Usage

### Controller

Flash messages service is helpful, when we want to give user some response information about controller action status.
Every message setting by this service is automatically translated by `Symfony\Component\Translation\Translation` with "messages" domain. For CRUD messages is used "crudMessages" domain.

Service provides several methods for add, set or get message.
Methods starting with "add" or "set", adds or sets messages to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.
which next we can display in view template.

Methods starting with "get" only prepare message and returns it without adding to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.
It's useful if you want to return translated message (for example if you work with REST api or Ajax request).

Get flash message service.

```php
public function indexAction()
{
    $flash = $this->get('ad_flash_message');
    ...
}
```


#### Set, add or get messages for any actions.

```php
/**
 * Sets and translates custom type flash message. Overrides previous message if was set.
 *
 * @param string        $type        Can be any string describing action status. For types: "success", "error", "notice"
 *                                   use dedicated methods "setSuccess", "setError", "setNotice".
 *
 * @param string|null   $message     Message text. Given text is always translated by "Symfony\Component\Translation\Translation"
 *                                   with "message" domain as default (of course if exists any translations for given string).
 *                                   If $message is null then will be dynamically creates as "translationId" based
 *                                   on called controller action name in convention: "company_bundle_controller.action.$type".
 *                                   For example, if we call this method (and $message is null) in controller
 *                                   "App\DemoBundle\Controller\ProductController::createAction"
 *                                   then will be generated this "translationId" value:     "app_demo_project.create.$type".
 *
 * @param array         $parameters  Parameters for translation message.
 * @param string|null   $domain      Translation domain. As default is "messages".
 */
public function set($type, $message = null, array $parameters = array(), $domain = null) {}

/**
 * Adds and translates custom type flash message.
 */
public function add($type, $message = null, array $parameters = array(), $domain = null) {}
```

Difference between methods "set" and "add" is obvious. "add" adds new message into flashBag array collection, while "set" override existing array messages collection by new one.

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

#### Set, add or get messages for CRUD actions.
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
 * @param string $entity       Persistence entity object or entity name. Is used as parameter %entity% in 
                               translation files.
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

###View

For display messages just call function "ad_flash_message"
```twig
    {{ ad_flash_messages() }}
```

<!--If you want add CSS styles for displaying messages...

Function "ad_flash_messages_class_name" returns css class name related to message type.
See "Resources/views/messages.html.twig" file.-->

