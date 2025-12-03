<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Utils;

/**
 * Data utility class for nested array manipulation
 * Provides methods to work with nested arrays using dot notation
 * 
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class Data
{
  protected bool $preserveNulls = false;
  protected array $data = [];

  /**
   * Enable or disable null value preservation
   * 
   * @param bool $preserve Whether to preserve null values
   * @return self
   */
  public function withPreserveNulls(bool $preserve = true)
  {
    $this->preserveNulls = $preserve;
    return $this;
  }

  /**
   * Set multiple values at once
   * 
   * @param array $data Data to set
   * @param bool $merge Whether to merge with existing data
   * @return self
   */
  public function sets(array $data, bool $merge = true): self
  {
    if (true === $merge) {
      $current = $this->flatten($this->data);
      $data = $this->flatten($data);
      $this->data = $this->unflatten([...$current, ...$data]);
      return $this;
    }

    $data = $this->unflatten($data);
    foreach ($data as $key => $value) {
      $this->set($key, $value);
    }

    return $this;
  }

  /**
   * Set a value using dot notation
   * 
   * @param string $path Dot notation path
   * @param mixed $value Value to set
   * @return self
   */
  public function set(string $path, mixed $value): self
  {
    $node = &$this->data; // Reference for direct modification
    $nodes = explode('.', $path); // Split the string into segments

    foreach ($nodes as $key) {
      // If the key is numeric, force conversion to integer
      $key = is_numeric($key) ? (int)$key : $key;

      // If the node doesn't exist or is not compliant, initialize it
      if (!isset($node[$key]) || (!is_array($node[$key]) && $key !== (int)$key)) {
        $node[$key] = [];
      }

      $node = &$node[$key]; // Move to the next level
    }

    // Assign the value to the final node
    $node = $value;

    return $this;
  }

  /**
   * Get a value using dot notation
   * 
   * @param string $path Dot notation path
   * @param mixed $default Default value if not found
   * @return mixed
   */
  public function get(string $path, mixed $default = null): mixed
  {
    $node = $this->fetchNode($path);

    return null === $node ? $default : $node;
  }

  /**
   * Get a boolean value
   * 
   * @param string $path Dot notation path
   * @param bool $default Default value if not found
   * @return bool
   */
  public function getBool(string $path, bool $default = false): bool
  {
    if (null === ($result = $this->get($path))) {
      return $default;
    }

    return true === $result || 1 === intval($result);
  }

  /**
   * Get an integer value
   * 
   * @param string $path Dot notation path
   * @param int $default Default value if not found
   * @return int
   */
  public function getInt(string $path, int $default = 0): int
  {
    $result = $this->get($path);

    if (null === $result) {
      return $default;
    }

    return intval($result);
  }

  /**
   * Get an array value
   * 
   * @param string $path Dot notation path
   * @param array $default Default value if not found
   * @return array
   */
  public function getArray(string $path, array $default = []): array
  {
    if (null === ($result = $this->get($path))) {
      return $default;
    }

    return (array)$result;
  }

  /**
   * Define a value with a default if it doesn't exist
   * 
   * @param string $path Dot notation path
   * @param mixed $default Default value to set
   * @return self
   */
  public function def(string $path, mixed $default = ''): self
  {
    $value = $this->get($path, $default);
    $this->set($path, $value);
    return $this;
  }

  /**
   * Check if a key exists
   * 
   * @param string $path Dot notation path
   * @return bool
   */
  public function has(string $path): bool
  {
    if (empty($path)) {
      return false;
    }

    if (!$this->preserveNulls) {
      return null !== ($this->fetchNode($path));
    }

    // When preserving nulls, check array key existence instead
    $node = &$this->data;
    $nodes = explode('.', $path);
    $lastKey = array_pop($nodes);

    foreach ($nodes as $key) {
      $key = is_numeric($key) ? (int)$key : $key;
      if (!isset($node[$key]) || !is_array($node[$key])) {
        return false;
      }
      $node = &$node[$key];
    }

    $lastKey = is_numeric($lastKey) ? (int)$lastKey : $lastKey;
    return array_key_exists($lastKey, $node);
  }

  /**
   * Remove a value using dot notation
   * 
   * @param string $path Dot notation path
   * @return self
   */
  public function erase(string $path): self
  {
    $node = &$this->data; // Reference for direct modification
    $nodes = explode('.', $path); // Split the string into segments
    $lastKey = array_pop($nodes); // Get the last key separately

    // Traverse array levels
    foreach ($nodes as $key) {
      // Handle numeric keys
      $key = is_numeric($key) ? (int)$key : $key;

      if (!isset($node[$key]) || !is_array($node[$key])) {
        return $this; // If an intermediate key is missing, stop
      }
      $node = &$node[$key]; // Go down one level
    }

    // Handle the final key
    $lastKey = is_numeric($lastKey) ? (int)$lastKey : $lastKey;
    if (isset($node[$lastKey])) {
      unset($node[$lastKey]); // Delete the final key
    }

    return $this;
  }

  /**
   * Get all data
   * 
   * @return array
   */
  public function all(): array
  {
    return $this->data;
  }

  /**
   * Flattens a nested array
   *
   * The scheme used is:
   *   'key' => ['key2' => ['key3' => 'value']]
   * Becomes:
   *   'key.key2.key3' => 'value'
   */
  protected function flatten(?array $data = null, string $separator = '.'): array
  {
    if (null === $data) {
      $data = $this->data;
    }

    $result = [];
    foreach ($data as $key => $value) {
      // Handle numeric keys
      $key = is_numeric($key) ? (int)$key : $key;

      if (is_array($value)) {
        // Recursive call for sub-levels
        foreach ($this->flatten($value, $separator) as $subKey => $subValue) {
          $result[$key . $separator . $subKey] = $subValue;
        }
      } elseif (null !== $value || $this->preserveNulls) {
        // Direct addition if the value is not an array
        $result[$key] = $value;
      }
    }

    return $result;
  }

  /**
   * Renders a multidimensional representation of the nested array
   *
   * The scheme used is:
   *   'key.key2.key3' => 'value'
   * Becomes:
   *   'key' => ['key2' => ['key3' => 'value']]
   */
  protected function unflatten(?array $data = null): array
  {
    $data = $this->flatten($data); // Flatten the data to ensure consistent processing
    $result = [];

    foreach ($data as $flatKey => $value) {
      $keys = explode('.', $flatKey); // Split the key by dots
      $current = &$result; // Reference to build the multidimensional array

      foreach ($keys as $key) {
        // Handle numeric keys
        $key = is_numeric($key) ? (int)$key : $key;

        if (!isset($current[$key]) || !is_array($current[$key])) {
          $current[$key] = []; // Create a new sub-structure if it doesn't exist
        }
        $current = &$current[$key];
      }

      $current = $value; // Assign the value to the last key
    }

    return $result;
  }

  private function &fetchNode(string $path): mixed
  {
    $node = &$this->data; // Reference for direct modification
    $nodes = explode('.', $path); // Split the string into segments
    $lastKey = array_pop($nodes); // Get the last key separately

    // Traverse array levels
    foreach ($nodes as $key) {
      // Handle numeric keys
      $key = is_numeric($key) ? (int)$key : $key;

      if (!isset($node[$key]) || !is_array($node[$key])) {
        $null = null; // To handle the reference in case of failure
        return $null; // Return a null reference if an intermediate key is missing
      }
      $node = &$node[$key]; // Go down one level
    }

    // Handle the final key
    $lastKey = is_numeric($lastKey) ? (int)$lastKey : $lastKey;
    if (isset($node[$lastKey])) {
      return $node[$lastKey]; // Return the reference to the value
    }

    $null = null; // Return a null reference if the final key doesn't exist
    return $null;
  }

  /***  Deprecated Methods ***/

  /**
   * @deprecated Use withPreserveNulls() instead
   */
  public function preserveNulls(bool $preserve = true): self
  {
    $this->withPreserveNulls($preserve);
    return $this;
  }
}
