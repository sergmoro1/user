Test manual
===========

How to start tests.

```
cd tests
../vendor/bin/codecept run
../vendor/bin/codecept run acceptance
../vendor/bin/codecept run acceptance CommentCept --steps --debug
```

How to start tests for extension.

```
cd vendor/sergmoro1/yii2-extension-name/src/tests
../../../../../vendor/bin/codecept run functional --steps
```

Before start
------------

Before you start the tests, you must create a database and run migrations.

Create database `yii2_tests`.
Change database name in config.
Start migrations in a root direcory of the application as mentioned in extension's `README.md`.

Configuration
-------------

Set `enablePrettyUrl` to `false` or comment it in config file.

```php
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => false,
```

yml
---

Change, if needed, `user` and `password` in tests config files. 

```
tests/acceptance.suite.yml
tests/functional.suite.yml
tests/unit.suite.yml
```

Change `webRoot` of the application in `tests/acceptance.suite.yml` if acceptance tests are used.

```
  config:
    PhpBrowser:
      url: http://localhost/your-app
```

Namespace
---------

After `*.suite.yml` have changed then automatically can be changed namespaces in a files `sergmoro1\user\tests\_support\*Tester.php` and
folders `sergmoro1\user\tests\_support\_generated`. Fix namespaces if needed.
For example in `FunctionalTesterActions.php`

```
// from
namespace sergmoro1\user\tests\_generated;

//to
namespace sergmoro1\user\tests\_support\_generated;
```
