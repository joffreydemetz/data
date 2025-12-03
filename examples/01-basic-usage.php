<?php

/**
 * Basic Usage Example
 * 
 * Demonstrates basic set, get, and has operations
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Basic Usage Example ===\n\n";

// Create a new Data instance
$data = new Data();

// Setting simple values
echo "1. Setting simple values:\n";
$data->set('name', 'John Doe');
$data->set('email', 'john@example.com');
$data->set('age', 30);

echo "   Name: " . $data->get('name') . "\n";
echo "   Email: " . $data->get('email') . "\n";
echo "   Age: " . $data->get('age') . "\n\n";

// Setting nested values using dot notation
echo "2. Setting nested values:\n";
$data->set('user.profile.name', 'Jane Smith');
$data->set('user.profile.email', 'jane@example.com');
$data->set('user.settings.theme', 'dark');
$data->set('user.settings.notifications', true);

echo "   User name: " . $data->get('user.profile.name') . "\n";
echo "   User theme: " . $data->get('user.settings.theme') . "\n\n";

// Getting values with defaults
echo "3. Getting values with defaults:\n";
echo "   Existing key: " . $data->get('name', 'Default') . "\n";
echo "   Non-existing key: " . $data->get('nonexistent', 'Default Value') . "\n\n";

// Checking if keys exist
echo "4. Checking key existence:\n";
echo "   'name' exists: " . ($data->has('name') ? 'Yes' : 'No') . "\n";
echo "   'user.profile.name' exists: " . ($data->has('user.profile.name') ? 'Yes' : 'No') . "\n";
echo "   'nonexistent' exists: " . ($data->has('nonexistent') ? 'Yes' : 'No') . "\n\n";

// Getting all data
echo "5. All data structure:\n";
print_r($data->all());
