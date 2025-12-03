<?php

/**
 * Erase and Def Example
 * 
 * Demonstrates erasing values and setting default values
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Erase and Def Example ===\n\n";

$data = new Data();

// Setting up initial data
$data->set('user.name', 'John Doe');
$data->set('user.email', 'john@example.com');
$data->set('user.profile.age', 30);
$data->set('user.profile.city', 'New York');

echo "1. Initial data:\n";
print_r($data->all());
echo "\n";

// Erasing values
echo "2. Erasing 'user.email':\n";
$data->erase('user.email');
echo "   Email exists: " . ($data->has('user.email') ? 'Yes' : 'No') . "\n";
print_r($data->all());
echo "\n";

echo "3. Erasing nested 'user.profile.city':\n";
$data->erase('user.profile.city');
echo "   City exists: " . ($data->has('user.profile.city') ? 'Yes' : 'No') . "\n";
print_r($data->all());
echo "\n";

// Using def (define with default)
echo "4. Using def() to set defaults:\n";
$data->def('user.country', 'USA'); // Will be set to 'USA' (doesn't exist)
$data->def('user.name', 'Default Name'); // Won't change (already exists)

echo "   Country (new): " . $data->get('user.country') . "\n";
echo "   Name (existing): " . $data->get('user.name') . "\n\n";

// Practical use case
echo "5. Practical use case - configuration with defaults:\n";
$config = new Data();
$config->set('app.debug', true);

// Set defaults for missing values
$config->def('app.debug', false);
$config->def('app.timeout', 30);
$config->def('app.retries', 3);
$config->def('app.cache.enabled', true);
$config->def('app.cache.ttl', 3600);

echo "   Final configuration:\n";
print_r($config->all());
