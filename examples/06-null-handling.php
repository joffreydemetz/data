<?php

/**
 * Null Handling Example
 * 
 * Demonstrates how null values are handled with preserveNulls option
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Null Handling Example ===\n\n";

// Default behavior (nulls are not preserved)
echo "1. Default behavior (preserveNulls = false):\n";
$data = new Data();
$data->set('user.name', 'John');
$data->set('user.email', null);
$data->set('user.age', 30);

echo "   Data structure:\n";
print_r($data->all());
echo "   'user.email' has value: " . ($data->has('user.email') ? 'Yes' : 'No') . "\n\n";

// With preserveNulls enabled
echo "2. With preserveNulls enabled:\n";
$data = new Data();
$data->withPreserveNulls(true);
$data->set('user.name', 'John');
$data->set('user.email', null);
$data->set('user.age', 30);

echo "   Data structure:\n";
print_r($data->all());
echo "   'user.email' has value: " . ($data->has('user.email') ? 'Yes' : 'No') . "\n";
echo "   Email value: ";
var_dump($data->get('user.email'));
echo "\n";

// Practical use case
echo "3. Practical use case - optional fields:\n";
$form = new Data();
$form->withPreserveNulls(true);

// Simulating form submission with optional fields
$form->set('contact.name', 'Jane Doe');
$form->set('contact.email', 'jane@example.com');
$form->set('contact.phone', null); // Optional field, not provided
$form->set('contact.company', null); // Optional field, not provided
$form->set('contact.message', 'Hello!');

echo "   Form data:\n";
print_r($form->all());
echo "   All fields are present, nulls included.\n";
