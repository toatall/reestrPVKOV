Реестр проверок органами государственного контроля и надзора
============================

Осуществляет ведение реестра проверок внешних контролирующих органов с реализованной функцией печати данных.
Основание разработки: исходящий документ № 6449-СЗ@ от 16.12.2016.

---


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



## Установка и настройка
Создаить базу данных и выполнить скрипт `db_schema.sql`

### Настрйка подключения к БД
Используется Microsoft SQL Server 2008 и выше.

Изменить файл `config/db.php` на реальные данные, пример:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlsrv:Server=server_name;Database=db',		
    'username' => 'user',
    'password' => 'password',	
];
```

## Изменения
* 01.02.2017 - разработка
* 08.05.2019 - изменение файла readme.md
