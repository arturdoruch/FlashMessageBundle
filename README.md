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

Get flash message service.

```php
public function indexAction()
{
    $flash = $this->get('arturdoruch_flash_message');
    // or simply
    $flash = $this->get('ad_flash');
    ...
}
```

Flash messages service is helpfull when we doing some actions and wanted to give back to user some response information about action status.
Setting message is automaticly transplated with domain: "messages" for normal message or "crudMessages" for crud actions message.

Service provides several methods for add, set or get flash message.
If we use methods starts with "add" or "set", then message will be added into `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.
But methods starts with "get" only prepare message and return her without adding to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.

All avaiable methods are created dynamicly and because of this your IDE, may not showing completitions.

Sets custom type translated flash message. Override previous message is was set.
```php set(string $type, $message = null, array $parameters = array(), string $domain = null)```

Instead sets $type by hand you can use these defined methods:
```php
    setSuccess($message = null, array $parameters = array(), string $domain = null)
    setError($message = null, array $parameters = array(), string $domain = null)
    setNotice($message = null, array $parameters = array(), string $domain = null)
```

Adds custom type translated flash message.
```php
    add(string $type, $message = null, array $parameters = array(), string $domain = null)

    addSuccess($message = null, array $parameters = array(), string $domain = null)
    addError($message = null, array $parameters = array(), string $domain = null)
    addNotice($message = null, array $parameters = array(), string $domain = null)
```

Difference between methods "set" and "add" is obvious. "Add" adds new message into array type collection, "Set" override existing array messages collection by new one. 

Gets custom type translated message. Not adds into session flash bug.
Just creates, translates and returns message.
```php
    get(string $type, $message = null, array $parameters = array(), string $domain = null)

    getSuccess($message = null, array $parameters = array(), string $domain = null)
    getError($message = null, array $parameters = array(), string $domain = null)
    getNotice($message = null, array $parameters = array(), string $domain = null)
```



 * Adds custom type CRUD action translated flash message.
 * @method addCrud(string $type, string $entity, $item = null, string $action = null)
 *
 * @method addCrudSuccess(string $entity, $item = null, string $action = null)
 * @method addCrudNotice(string $entity, $item = null, string $action = null)
 * @method addCrudError(string $entity, $item = null, string $action = null)
 *
 * Gets custom type CRUD action translated message.
 * @method getCrud(string $type, string $entity, $item = null, string $action = null)
 *
 * @method getCrudSuccess(string $entity, $item = null, string $action = null)
 * @method getCrudNotice(string $entity, $item = null, string $action = null)
 * @method getCrudError(string $entity, $item = null, string $action = null)


###View

For display messages just call function "ad_flash_message"
```twig
    {{ ad_flash_messages() }}
```

<!--If you want add CSS styles for displaying messages...

Function "ad_flash_messages_class_name" returns css class name related to message type.
See "Resources/views/messages.html.twig" file.-->

