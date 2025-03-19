<?php

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data as jData;

// Initialization
$data = new jData();

// Setting Values
$data->set('user.name.first', 'John');
$data->set('user.name.last', 'Doe');

// Getting Values
$firstName = $data->get('user.name.first'); // "John"
$lastName = $data->get('user.name.last', 'Default Name'); // "Doe"
$nonExistent = $data->get('user.nonexistent', 'Default Value'); // "Default Value"

// Checking for Existence
if ($data->has('user.name.first')) {
    echo "First name exists." . "\n";
} else {
    echo "First name does not exist." . "\n";
}

if ($data->has('user.name.second')) {
    echo "Middle name exists." . "\n";
} else {
    echo "Middle name does not exist." . "\n";
}

$data->set('user.name.second', null);

if ($data->has('user.name.second')) {
    echo "Middle name exists." . "\n";
} else {
    echo "Middle name does not exist." . "\n";
}

$data->set('user.name.second', '');

if ($data->has('user.name.second')) {
    echo "Middle name exists." . "\n";
} else {
    echo "Middle name does not exist." . "\n";
}

class Data extends jData
{
    public function toDot(array $arrayData): array
    {
        return $this->flatten($arrayData);
    }

    public function toArray(array $dotData): array
    {
        return $this->unflatten($dotData);
    }
}

// Flattening Data
$yourData = new Data();
$flattened = $yourData->toDot(['user' => ['name' => ['first' => 'John', 'last' => 'Doe']]]);
// [ 'user.name.first' => 'John', 'user.name.last' => 'Doe' ]

// Unflattening Data
$yourData = new Data();
$array = $yourData->toArray(['user.name.first' => 'John', 'user.name.last' => 'Doe']);
// [ 'user' => [ 'name' => [ 'first' => 'John', 'last' => 'Doe' ] ] ]

// Merging Data
$data->sets([
    'user.name.middle' => 'Edward',
    'user.age' => 30
], true);

// Deleting Values
$data->erase('user.name.middle');
