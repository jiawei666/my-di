### My-DI
![](https://img.shields.io/badge/build-passing-green)

### Introduce
The Better `PHP` DI Container

### Install
```shell script
composer require jiawei666/my-di
```

### Get Started

```php

$container = new MyDI\Container(YourClass::class);  // Init container
$Object    = $container->get(); // Get object
// ...

// See unit test Amway for more usage 
```