<?php

/**
 * Typed Getters Example
 * 
 * Demonstrates getBool, getInt, and getArray methods
 */

require __DIR__ . '/../vendor/autoload.php';

use JDZ\Utils\Data;

echo "=== Typed Getters Example ===\n\n";

$data = new Data();

// Boolean values
echo "1. Boolean values:\n";
$data->set('settings.enabled', true);
$data->set('settings.disabled', false);
$data->set('settings.numericOne', 1);
$data->set('settings.numericZero', 0);
$data->set('settings.stringOne', '1');

echo "   enabled (true): " . ($data->getBool('settings.enabled') ? 'true' : 'false') . "\n";
echo "   disabled (false): " . ($data->getBool('settings.disabled') ? 'true' : 'false') . "\n";
echo "   numericOne (1): " . ($data->getBool('settings.numericOne') ? 'true' : 'false') . "\n";
echo "   numericZero (0): " . ($data->getBool('settings.numericZero') ? 'true' : 'false') . "\n";
echo "   stringOne ('1'): " . ($data->getBool('settings.stringOne') ? 'true' : 'false') . "\n";
echo "   nonexistent (default=false): " . ($data->getBool('settings.nonexistent') ? 'true' : 'false') . "\n";
echo "   nonexistent (default=true): " . ($data->getBool('settings.nonexistent', true) ? 'true' : 'false') . "\n\n";

// Integer values
echo "2. Integer values:\n";
$data->set('stats.count', '42');
$data->set('stats.score', 100);
$data->set('stats.invalid', 'not a number');

echo "   count ('42'): " . $data->getInt('stats.count') . "\n";
echo "   score (100): " . $data->getInt('stats.score') . "\n";
echo "   invalid ('not a number'): " . $data->getInt('stats.invalid') . "\n";
echo "   nonexistent (default=0): " . $data->getInt('stats.nonexistent') . "\n";
echo "   nonexistent (default=999): " . $data->getInt('stats.nonexistent', 999) . "\n\n";

// Array values
echo "3. Array values:\n";
$data->set('lists.tags', ['php', 'data', 'utility']);
$data->set('lists.string', 'single value');

echo "   tags (array):\n";
print_r($data->getArray('lists.tags'));
echo "   string (cast to array):\n";
print_r($data->getArray('lists.string'));
echo "   nonexistent (default=[]):\n";
print_r($data->getArray('lists.nonexistent'));
echo "   nonexistent (custom default):\n";
print_r($data->getArray('lists.nonexistent', ['default', 'values']));
