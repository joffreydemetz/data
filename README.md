# Data

The `Data` class is a utility for managing deeply nested associative arrays and objects. It allows for manipulation, traversal, and transformation of data using dot notation for keys. This is particularly useful for complex data structures where direct access or modification is cumbersome.

## Features

- **Dot Notation Access**: Access, set, or delete deeply nested keys using a simple `dot.notation` syntax.
- **Flattening and Unflattening**: Convert nested arrays to a flat structure with dot notation and vice versa.
- **Type-Specific Retrieval**: Fetch values as specific types such as `int`, `bool`, or `array`.
- **Default Value Handling**: Retrieve values with fallback defaults if the key does not exist.
- **Merge and Overwrite**: Merge new data into existing data with fine-grained control.

## Installation

To include this class in your project, use composer:

```bash
composer require jdz/data
```

## Usage

### Initialization

```php
use JDZ\Utils\Data;

$data = new Data();
```

### Setting Values

```php
$data->set('user.name.first', 'John');
$data->set('user.name.last', 'Doe');
```

### Getting Values

```php
$firstName = $data->get('user.name.first'); // "John"
$lastName = $data->get('user.name.last', 'Default Name'); // "Doe"
$nonExistent = $data->get('user.nonexistent', 'Default Value'); // "Default Value"
```

### Checking for Existence

```php
if ($data->has('user.name.first')) {
    echo "First name exists.";
}
```

### Flattening Data
Protected method, can be used in a derived class
```php
class YourData extends \JDZ\Utils\Data
{
    public function toDot(array $arrayData): array
    {
        return $this->flatten($arrayData);
    }
}

$yourData = new YourData();
$flattened = $yourData->toDot([ 'user' => [ 'name' => [ 'first' => 'John', 'last' => 'Doe' ] ] ]);
// [ 'user.name.first' => 'John', 'user.name.last' => 'Doe' ]
```

### Unflattening Data
Protected method, can be used in a derived class
```php
class YourData extends \JDZ\Utils\Data
{
    public function toArray(array $dotData): array
    {
        return $this->unflatten($dotData);
    }
}

$yourData = new YourData();
$array = $yourData->toArray([ 'user.name.first' => 'John', 'user.name.last' => 'Doe' ]);
// [ 'user' => [ 'name' => [ 'first' => 'John', 'last' => 'Doe' ] ] ]
```

### Merging Data

```php
$data->sets([
    'user.name.middle' => 'Edward',
    'user.age' => 30
], true);
```

### Deleting Values

```php
$data->erase('user.name.middle');
```

## Methods

### Public Methods

| Method       | Description |
|--------------|-------------|
| `set()`      | Sets a value at the specified path. |
| `get()`      | Retrieves a value from the specified path, with an optional default. |
| `getBool()`  | Retrieves a boolean value from the specified path. |
| `getInt()`   | Retrieves an integer value from the specified path. |
| `getArray()` | Retrieves an array value from the specified path. |
| `def()`      | Sets a default value if the key does not exist. |
| `has()`      | Checks if a value exists at the specified path. |
| `erase()`    | Deletes a value at the specified path. |
| `all()`      | Returns all stored data. |
| `flatten()`  | Flattens the data into a single-dimensional array. |
| `unflatten()`| Converts a flattened array back into a nested structure. |

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Author

(c) Joffrey Demetz <joffrey.demetz@gmail.com>