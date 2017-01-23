# CakePHP Seeder Plugin

A plugin to plant some seeds in your application.

## Installation

You may install the Seeder Plugin through [Composer](http://getcomposer.org) or
[download](https://github.com/andtxr/cakephp-seeder/archive/master.zip) the source.

### Composer

``composer require andtxr/cakephp-seeder``

### Source

[Download](https://github.com/andtxr/cakephp-seeder/archive/master.zip) the source
and unpack it contents inside ``/Plugin/Seeder``

## Activation

Add the following to your ``Config/bootstrap.php``:

```php
    CakePlugin::load('Seeder');
```

## Usage

Create the folder ``Config/Seeds`` and then put the seed files inside of it:

```php
<?php

App::uses('Seed', 'Seeder.Config/Seeds');

class UserSeed extends Seed {

    public static function getSeed()
    {
        $faker = Faker\Factory::create();
        return array(
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => 'abc123'
        );
    }

}

?>
```

Save it as ``UserSeed.php``.

Try to change the file to match a model of your CakePHP app. 

Now use the shell command:

```shell
Console/cake seeder.plant -t -q 25 user
```

You can use the following options:

- --quantity, -q - Quantity of seeds to be planted. (default:15)
- --truncate, -t - Truncate the model related table.