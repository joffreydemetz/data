<?php

/**
 * @author    Joffrey Demetz <joffrey.demetz@gmail.com>
 * @license   MIT License; <https://opensource.org/licenses/MIT>
 */

namespace JDZ\Utils;

/**
 * Interface for nested array manipulation using dot notation
 *
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
interface DataInterface
{
  /**
   * Enable or disable null value preservation
   *
   * @param bool $preserve Whether to preserve null values
   * @return self
   */
  public function withPreserveNulls(bool $preserve = true): self;

  /**
   * Set multiple values at once
   *
   * @param array $data Data to set
   * @param bool $merge Whether to merge with existing data
   * @return self
   */
  public function sets(array $data, bool $merge = true): self;

  /**
   * Set a value using dot notation
   *
   * @param string $path Dot notation path
   * @param mixed $value Value to set
   * @return self
   */
  public function set(string $path, mixed $value): self;

  /**
   * Get a value using dot notation
   *
   * @param string $path Dot notation path
   * @param mixed $default Default value if not found
   * @return mixed
   */
  public function get(string $path, mixed $default = null): mixed;

  /**
   * Get a boolean value
   *
   * @param string $path Dot notation path
   * @param bool $default Default value if not found
   * @return bool
   */
  public function getBool(string $path, bool $default = false): bool;

  /**
   * Get an integer value
   *
   * @param string $path Dot notation path
   * @param int $default Default value if not found
   * @return int
   */
  public function getInt(string $path, int $default = 0): int;

  /**
   * Get an array value
   *
   * @param string $path Dot notation path
   * @param array $default Default value if not found
   * @return array
   */
  public function getArray(string $path, array $default = []): array;

  /**
   * Define a value with a default if it doesn't exist
   *
   * @param string $path Dot notation path
   * @param mixed $default Default value to set
   * @return self
   */
  public function def(string $path, mixed $default = ''): self;

  /**
   * Check if a key exists
   *
   * @param string $path Dot notation path
   * @return bool
   */
  public function has(string $path): bool;

  /**
   * Remove a value using dot notation
   *
   * @param string $path Dot notation path
   * @return self
   */
  public function erase(string $path): self;

  /**
   * Get all data
   *
   * @return array
   */
  public function all(): array;
}
