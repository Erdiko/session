#Erdiko Session Package

[![Package version](https://img.shields.io/packagist/v/erdiko/erdiko.svg?style=flat-square)](https://packagist.org/packages/erdiko/erdiko)
[![CircleCI](https://circleci.com/gh/Erdiko/erdiko.svg?style=svg)](https://circleci.com/gh/Erdiko/erdiko)
[![License](https://poser.pugx.org/erdiko/erdiko/license)](https://packagist.org/packages/erdiko/erdiko)

## Important: This package is currently on development, please don't use it until have a beta or stable version.

### Introduction

Erdiko Session is a package to handle on a easy and elastic way your persistent data through the lifetime of your application.

#### Official Documentation

Documentation for Erdiko Session Package can be found on the [Erdiko website](http://erdiko.org/session/).

#### Installation

We recommend installing Erdiko Session Package with [composer](here https://getcomposer.org/).  At the commandline simply run:
```
$ composer require erdiko/session
```

#### Drivers

Current Drivers Available:
 - Session_Driver_File

Coming Drivers in development:
 - Session_Driver_Database
 - Session_Driver_Cookie

#### Dependencies

This package depends of Erdiko\Core

#### Configuration

File Path
```
$ site_root/app/config/default/session.json
```

File format
```
{
  "default": { // Driver Source
    "driver": "file",  // Driver Type
    "path": "/tmp",
    "lifetime": 60000
  }
}
```
#### Available Methods

You will find several methods that will satisfy your requirements for handling session data.

 - get
 - set
 - has
 - forget
 - exists

#### Basic Usage
For more details please see [*Advance Usage Detail*](/advanceUsage.md) Page.

##### Get method
Retrieves the value from the session
```
Session::get('name');
```
##### Set a value
Set the value on session
```
Session::set('name', 'value');
```
##### Has method
Verifies if the given key exists and has a value on the session
```
Session::get('name');
```
##### Exists a value
Verifies if the given key exists, without verify if has or not value
```
Session::set('name', 'value');
```
##### Forget a value
Removes the given key from the session
```
Session::set('name', 'value');
```

#### Tests
*On development*

### Credits

* John Arroyo
* Andy Armstrong
* Leo Daidone

[All Contributors](https://github.com/Erdiko/erdiko/graphs/contributors)

* If you want to help, please do, we'd love more brainpower!  Fork, commit your enhancements and do a pull request.  If you want to get to even more involved please contact us!

### Sponsors

[Arroyo Labs](http://www.arroyolabs.com/)


### License

Erdiko is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)