# SlimTpl.php
A very small template engine for PHP

## Usage
```php
SlimTpl::compile_file([source], [vars], [target]);
```

## Example
```php
SlimTpl::compile_file('home.tpl.php', [
  '$baseurl' => '',
  '$siteurl' => 'http://comidalivre.org',
  '$is_deploy' => 1,
], 'compiled_home.html');
```
