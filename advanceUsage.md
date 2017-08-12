# Erdiko Session Package

## Advance usage

Here you will find how to use on details the Erdiko Session Package

### How it works
If you need more details regarding how Erdiko Session Package works please visit our [official documentation page](http://erdiko.org/session/).
 
### Methods

##### Set method

Available params:
 - key
 - value
 - locked
 - expire
 
```
Session::($key, $value, $locked=false, $expire=null);
```

##### Get method

Available params:
 - key
 
```
Session::get($key);
```