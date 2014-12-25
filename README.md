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

###View

For display messages just call function "ad_flash_message"
```twig
    {{ ad_flash_messages() }}
```

<!--If you want add CSS styles for displaying messages...

Function "ad_flash_messages_class_name" returns css class name related to message type.
See "Resources/views/messages.html.twig" file.-->

