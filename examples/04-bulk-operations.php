<?php

/**
 * Bulk Operations Example
 * 
 * Demonstrates sets (bulk setting) with merge options
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Bulk Operations Example ===\n\n";

// Example 1: Merge mode (default)
echo "1. Merge mode (preserves existing data):\n";
$data = new Data();
$data->set('user.name', 'John');
$data->set('user.email', 'john@example.com');

echo "   Before merge:\n";
print_r($data->all());

$data->sets([
    'user.age' => 30,
    'user.city' => 'New York',
], true);

echo "   After merge (added age and city):\n";
print_r($data->all());
echo "\n";

// Example 2: No merge mode
echo "2. No merge mode (sets each key individually):\n";
$data = new Data();
$data->set('config.debug', true);
$data->set('config.timeout', 30);

echo "   Before sets:\n";
print_r($data->all());

$data->sets([
    'config.cache' => true,
    'config.log.level' => 'info',
    'config.log.file' => 'app.log',
], false);

echo "   After sets (added new nested structures):\n";
print_r($data->all());
echo "\n";

// Example 3: Overwriting with merge
echo "3. Overwriting existing values with merge:\n";
$data = new Data();
$data->set('app.version', '1.0.0');
$data->set('app.name', 'MyApp');

echo "   Before:\n";
print_r($data->all());

$data->sets([
    'app.version' => '2.0.0',
    'app.status' => 'stable',
], true);

echo "   After (version updated, status added):\n";
print_r($data->all());
