<?php

/**
 * Array Keys Example
 * 
 * Demonstrates working with numeric keys and arrays
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Array Keys Example ===\n\n";

$data = new Data();

// Setting array items using numeric keys
echo "1. Building an array structure:\n";
$data->set('users.0.name', 'John Doe');
$data->set('users.0.email', 'john@example.com');
$data->set('users.0.role', 'admin');

$data->set('users.1.name', 'Jane Smith');
$data->set('users.1.email', 'jane@example.com');
$data->set('users.1.role', 'user');

$data->set('users.2.name', 'Bob Johnson');
$data->set('users.2.email', 'bob@example.com');
$data->set('users.2.role', 'user');

echo "   User 0 name: " . $data->get('users.0.name') . "\n";
echo "   User 1 email: " . $data->get('users.1.email') . "\n";
echo "   User 2 role: " . $data->get('users.2.role') . "\n\n";

// Getting the full array
echo "2. Full users array:\n";
print_r($data->getArray('users'));
echo "\n";

// Mixed numeric and string keys
echo "3. Mixed key types:\n";
$data->set('config.servers.0', 'server1.example.com');
$data->set('config.servers.1', 'server2.example.com');
$data->set('config.timeout', 30);
$data->set('config.retries', 3);

echo "   Config structure:\n";
print_r($data->getArray('config'));
