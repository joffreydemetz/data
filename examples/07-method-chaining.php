<?php

/**
 * Method Chaining Example
 * 
 * Demonstrates fluent interface with method chaining
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Method Chaining Example ===\n\n";

// Building a configuration with chaining
echo "1. Building configuration with chaining:\n";
$config = (new Data())
    ->set('app.name', 'MyApp')
    ->set('app.version', '1.0.0')
    ->set('app.debug', true)
    ->def('app.timeout', 30)
    ->def('app.retries', 3);

print_r($config->all());
echo "\n";

// Setting up user data with chaining
echo "2. Setting up user data:\n";
$user = (new Data())
    ->set('profile.name', 'John Doe')
    ->set('profile.email', 'john@example.com')
    ->set('settings.theme', 'dark')
    ->set('settings.notifications', true)
    ->def('settings.language', 'en')
    ->def('profile.avatar', 'default.png');

print_r($user->all());
echo "\n";

// Complex operations with chaining
echo "3. Complex operations:\n";
$data = (new Data())
    ->withPreserveNulls(true)
    ->sets([
        'api.endpoint' => 'https://api.example.com',
        'api.version' => 'v1',
        'api.timeout' => 30,
    ])
    ->set('api.key', null) // Will be preserved
    ->def('api.retries', 3)
    ->erase('api.timeout') // Remove timeout
    ->set('api.timeout', 60); // Set new timeout

print_r($data->all());
