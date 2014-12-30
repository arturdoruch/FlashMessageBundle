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

    /**
     * Gets custom type translated flash message.
     * This method not adds message into session flash bug.
     * Just creates, translates and returns it.
     */
    public function get($type, $message = null, array $parameters = array(), $domain = null) {}

    /**
     * Other available methods sets, adds or gets messages with concrete types: "Success", "Error", "Notice".
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

Instead sets $type by hand you can use these convenient methods.



Difference between methods "set" and "add" is obvious. "add" adds new message into flashBag array collection, while "set" override existing array messages collection by new one.


#### Set, add or get messages for CRUD actions.

These methods inteligent sets message for crud actions. 
Suppose we have entity class `Acme\DemoBundle\Entity\Product` and this entity has property `$name` and acessor method. `get`.

```php 
    // Adds custom type CRUD action translated flash message.
    public function addCrud(string $type, string $entity, $item = null, string $action = null)
```
<dl>
    <dt>$type</dt>
    <dd><b>type</b>: string <b>required</b></dd>
    <dd>Message type. It's might be any string. For types: "success", "error", "notice" use dedicated methods.
    </dd>
    
    <dt>$entity</dt>
    <dd><b>type</b>: object|string <b>required</b></dd>
    <dd>Persistence object entity or simply entity name.</dd>
    
    <dt>$item</dt>
    <dd><b>type</b>: string</dd>
    <dd>Entity item name.</dd>
    
    <dt>$action</dt>
    <dd><b>type</b>: string</dd>
    <dd>Action name.</dd>
</dl>

```php
    public function addCrudSuccess(string $entity, $item = null, string $action = null)
    public function addCrudNotice(string $entity, $item = null, string $action = null)
    public function addCrudError(string $entity, $item = null, string $action = null)
    
    // Gets custom type CRUD action translated message.
    public function getCrud(string $type, string $entity, $item = null, string $action = null)
    
    public function getCrudSuccess(string $entity, $item = null, string $action = null)
    public function getCrudNotice(string $entity, $item = null, string $action = null)
    public function getCrudError(string $entity, $item = null, string $action = null)
```


###View

For display messages just call function "ad_flash_message"
```twig
    {{ ad_flash_messages() }}
```

<!--If you want add CSS styles for displaying messages...

Function "ad_flash_messages_class_name" returns css class name related to message type.
See "Resources/views/messages.html.twig" file.-->

