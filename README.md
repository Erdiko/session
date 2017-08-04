# Erdiko Session Handler

#### Important: This package is currently on development, please don't use it until have a beta or stable version.

This package must be used to persist any kind of data through the lifetime of your app.

Current Drivers Available:
 - Session_Driver_File

Coming Drivers in development:
 - Session_Driver_Database

#### Dependencies

This package depends of Erdiko\Core

#### Configuration

File Path: root/app/config/default/session.json

```
{
  "default": "session",
  "config": {
    "path": "/tmp",
    "lifetime": 60000
  }
}
```
 
#### Usage

Set a value

```
Session::set('name', 'value');
```

Get a value

```
Session::get('name');
```