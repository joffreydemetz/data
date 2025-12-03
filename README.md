# JDZ Data

A PHP utility class for manipulating nested arrays using dot notation.

## Features

- 🎯 **Dot Notation**: Access nested array values with simple dot notation (`user.profile.name`)
- 🔢 **Numeric Keys**: Work seamlessly with array indices
- 🔄 **Bulk Operations**: Set multiple values at once with merge support
- 🎨 **Typed Getters**: Type-safe retrieval with `getBool()`, `getInt()`, `getArray()`
- 🔗 **Method Chaining**: Fluent interface for elegant code
- 🎭 **Null Handling**: Optional null value preservation
- ✅ **Type Safe**: Full PHP 8.0+ type declarations

## Installation

```bash
composer require jdz/data
```

## Requirements

- PHP 8.0 or higher

## Quick Start

```php
use JDZ\Utils\Data;

$data = new Data();

// Set values using dot notation
$data->set('user.name', 'John Doe');
$data->set('user.email', 'john@example.com');
$data->set('user.settings.theme', 'dark');

// Get values
echo $data->get('user.name'); // "John Doe"
echo $data->get('user.age', 30); // 30 (default value)

// Check existence
if ($data->has('user.email')) {
    echo "Email exists!";
}

// Work with arrays
$data->set('users.0.name', 'John');
$data->set('users.1.name', 'Jane');

// Get typed values
$isActive = $data->getBool('user.active', true);
$count = $data->getInt('user.login_count', 0);
$tags = $data->getArray('user.tags', []);
```

## Documentation

### Setting Values

#### `set(string $path, mixed $value): self`

Set a single value using dot notation.

```php
$data->set('app.name', 'MyApp');
$data->set('app.version', '1.0.0');
$data->set('config.database.host', 'localhost');
```

#### `sets(array $data, bool $merge = true): self`

Set multiple values at once.

```php
// Merge with existing data
$data->sets([
    'api.endpoint' => 'https://api.example.com',
    'api.timeout' => 30,
], true);

// Set without merging
$data->sets([
    'config.debug' => true,
], false);
```

### Getting Values

#### `get(string $path, mixed $default = null): mixed`

Get a value with an optional default.

```php
$name = $data->get('user.name');
$country = $data->get('user.country', 'USA');
```

#### `getBool(string $path, bool $default = false): bool`

Get a boolean value. Converts `1`, `'1'`, and `true` to `true`.

```php
$isEnabled = $data->getBool('features.api');
$hasAccess = $data->getBool('user.premium', false);
```

#### `getInt(string $path, int $default = 0): int`

Get an integer value with automatic type conversion.

```php
$timeout = $data->getInt('config.timeout');
$retries = $data->getInt('config.retries', 3);
```

#### `getArray(string $path, array $default = []): array`

Get an array value. Non-array values are cast to arrays.

```php
$tags = $data->getArray('post.tags');
$items = $data->getArray('list.items', ['default']);
```

### Checking and Removing

#### `has(string $path): bool`

Check if a key exists.

```php
if ($data->has('user.email')) {
    // Email is set
}
```

#### `erase(string $path): self`

Remove a value.

```php
$data->erase('user.temporary_token');
$data->erase('cache.expired');
```

#### `def(string $path, mixed $default = ''): self`

Set a value only if it doesn't already exist.

```php
$data->def('config.timeout', 30); // Sets if not exists
$data->def('config.retries', 3);  // Sets if not exists
```

### Utility Methods

#### `all(): array`

Get all data as an array.

```php
$allData = $data->all();
```

#### `preserveNulls(bool $preserve = true): self`

Enable or disable null value preservation.

```php
$data->preserveNulls(true);
$data->set('user.optional', null); // Null is preserved
```

## Working with Arrays

Access array elements using numeric keys:

```php
$data->set('items.0', 'First');
$data->set('items.1', 'Second');
$data->set('items.2', 'Third');

echo $data->get('items.0'); // "First"
```

Build complex structures:

```php
$data->set('users.0.name', 'John');
$data->set('users.0.email', 'john@example.com');
$data->set('users.1.name', 'Jane');
$data->set('users.1.email', 'jane@example.com');

$users = $data->getArray('users');
// [
//   ['name' => 'John', 'email' => 'john@example.com'],
//   ['name' => 'Jane', 'email' => 'jane@example.com']
// ]
```

## Method Chaining

All mutating methods return `$this` for fluent chaining:

```php
$config = (new Data())
    ->set('app.name', 'MyApp')
    ->set('app.version', '1.0.0')
    ->def('app.debug', false)
    ->def('app.timeout', 30)
    ->erase('app.temporary');
```

## Examples

See the [examples](examples/) directory for detailed examples:

- `01-basic-usage.php` - Basic operations
- `02-typed-getters.php` - Typed getter methods
- `03-array-keys.php` - Working with arrays
- `04-bulk-operations.php` - Bulk setting operations
- `05-erase-and-def.php` - Removing and defaulting values
- `06-null-handling.php` - Null value handling
- `07-method-chaining.php` - Method chaining examples

Run example:

```bash
php examples/01-basic-usage.php
```

## Testing

Run all tests:

```bash
composer test
# or
vendor/bin/phpunit
```

Run tests with coverage report (HTML):

```bash
composer test-coverage
# Coverage report will be generated in the coverage/ directory
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
