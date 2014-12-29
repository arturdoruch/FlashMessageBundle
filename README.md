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

Flash messages service is helpful, when we want to give user some response information about action status.
Every message set by this service is automatically translated by `Symfony\Component\Translation\Translation` with "messages" domain.
For CRUD actions by default is uses "crudMessages" domain.

Service provides several methods for add, set or get flash message.
Methods starts with "add" or "set", added messages into `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.
Which next we can display them in view template.

Methods starts with "get" only prepare message and returns it without adding to `Symfony\Component\HttpFoundation\Session\Flash\FlashBag`.
It's useful if you want to return translated message (for example if you work with REST api or Ajax request).


All available methods in service `arturdoruch_flash.message` are created dynamically. They are of two types: 
<ol>
    <li>Messages for CRUD actions.

These methods inteligent sets message for crud actions. 
Suppose we have entity class `Acme\DemoBundle\Entity\Product` and this entity has property `$name` and acessor method. `get`.
Now when we ...

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
</li>
<li>Messages for anything other actions.

```php
    // Sets custom type translated flash message. Override previous message is was set.
    public function set(string $type, $message = null, array $parameters = array(), string $domain = null)
```

<dl>
    <dt>$type</dt>
    <dd><b>type</b>: string <b>required</b></dd>
    <dd>Message type. It's might be any string. For types: "success", "error", "notice" use dedicated methods.
    </dd>
    
    <dt>$message</dt>
    <dd><b>type</b>: string</dd>
    <dd>If null then message will be dinamically create as translation key based on controller action name. Assumed we have controller `Acme\DemoBunlde\Controller\ProjectController::createAction` key will be creates in convention: `acme_demo_project.create.$type`.
    </dd>
    
    <dt>$parameters</dt>
    <dd><b>type</b>: array</dd>
    <dd>Parameters for translator message.</dd>
    
    <dt>$domain</dt>
    <dd><b>type</b>: string <b>default</b>: "messages"</dd>
    <dd>Translation domain.</dd>
</dl>

Instead sets $type by hand you can use these convenient methods:
```php
    public function setSuccess($message = null, array $parameters = array(), string $domain = null)
    public function setError($message = null, array $parameters = array(), string $domain = null)
    public function setNotice($message = null, array $parameters = array(), string $domain = null)
```

Other methods

```php
    // Adds custom type translated flash message
    public function add(string $type, $message = null, array $parameters = array(), string $domain = null)

    public function addSuccess($message = null, array $parameters = array(), string $domain = null)
    public function addError($message = null, array $parameters = array(), string $domain = null)
    public function addNotice($message = null, array $parameters = array(), string $domain = null)

    // Gets custom type translated message. Not adds into session flash bug.
    // Just creates, translates and returns message.
    public function get(string $type, $message = null, array $parameters = array(), string $domain = null)

    public function getSuccess($message = null, array $parameters = array(), string $domain = null)
    public function getError($message = null, array $parameters = array(), string $domain = null)
    public function getNotice($message = null, array $parameters = array(), string $domain = null)
```

Difference between methods "set" and "add" is obvious. "Add" adds new message into array flashBag collection, while "Set" override existing array messages collection by new one.

</li>
</ol>

###View

For display messages just call function "ad_flash_message"
```twig
    {{ ad_flash_messages() }}
```

<!--If you want add CSS styles for displaying messages...

Function "ad_flash_messages_class_name" returns css class name related to message type.
See "Resources/views/messages.html.twig" file.-->

