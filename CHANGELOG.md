# Changelog

All Notable changes to `Slick` will be documented in this file

## NEXT - YYYY-MM-DD

### Added
- Annotations now make use of class namespace and use statements to
  be imported
- `Slick\Common\Inspector` now manages a list of class metadata for better
  performance. The static `Slick\Common\Inspector::forClass()` method should
  now be used to get class information and annotations
- `Slick\Database\RecordList::asArray()` To retrieve the record list data
  as an array
- Support for transaction in database adapters. Adapter API exposes
  `beginTransaction()`, `commit()` and `rollback()` methods directly.
- Multiple domain on translation (I18n)
- Multiple locales on translation (I18n)
- Set the translator types as Translator class constants:
  TYPE_PHP_ARRAY and TYPE_GETTEXT  

### Deprecated
- `Slick\Database\RecordList::getArrayCopy()` you should use
  `Slick\Database\RecordList::asArray()`
- `Slick\Configuration\Driver\DriverInterface` interface use a namespace and
  path witch is ambiguous when used with other packages. You should use
  `Slick\Configuration\ConfigurationInterface`

### Fixed
- Nothing

### Removed
- Codeception support
- `Slick\Common\Inspector::__construct()` cannot be used anymore
- `Slick\Utility` module was removed and its now on `Slick\Common\Utils` namespace
- `Slick\Common\SingletonInterface` and `Slick\Common\BaseSingleton`. You can use the
  `Slick\Common\BaseMethods` trait in any class you want.

### Security
- Nothing

## 1.1.0 - 2015-04-14

### Added
- Adding travis support
- New `Database` component with `Sql` factory and more robust interfaces
- Schema loader factory
- Rewrite `MVC` component to work with new `ORM` module
- Filter event on `MVC` application
- Whoops as a error handler.

### Fixed
- Multiple bugs `ORM` component
- Connection validation on memcached driver.
- Bug on router extension attribution.
- Bug on delete session keys.
- Bug on multiple relations when loading join values.
- Model name parsing on generate commands.

## 1.0.5 - 2014-10-06

### Fixed
- Bug on elect count when it does not returns any row.

## 1.0.4 - 2014-10-02

### Fixed
- Fix the count with multiple joins.

## 1.0.3 - 2014-09-17

### Fixed
- Fix the count with multiple joins.

## 1.0.2 - 2014-09-17

### Added
- Select event trigger for count in entities.

## 1.0.1 - 2014-06-05 

### Fixed
- Fixing bug on ORM relations

## 1.0.0 - 2014-06-04 

### Added
- First release of Slick framework!