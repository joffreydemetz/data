<?php

/**
 * (c) Joffrey Demetz <joffrey.demetz@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JDZ\Utils;

/**
 * Data
 * 
 * @author Joffrey Demetz <joffrey.demetz@gmail.com>
 */
class Data
{
  protected array $data = [];

  public function sets(array $data, bool $merge = true)
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

  public function set(string $path, mixed $value)
  {
    $node = &$this->data; // Référence pour modification directe
    $nodes = explode('.', $path); // Découpe la chaîne en segments

    // Traverse ou crée les niveaux du tableau
    foreach ($nodes as $key) {
      if (!isset($node[$key]) || !is_array($node[$key])) {
        $node[$key] = []; // Crée le nœud s'il n'existe pas
      }
      $node = &$node[$key]; // Passe au niveau suivant
    }
    $node = $value;
    return $this;
  }

  public function get(string $path, mixed $default = null): mixed
  {
    if (false !== ($node = $this->fetchNode($path))) {
      return $node;
    }
    return $default;
  }

  public function getBool(string $path, bool $default = false): bool
  {
    if (null === ($result = $this->get($path))) {
      return $default;
    }

    return true === $result || 1 === intval($result);
  }

  public function getInt(string $path, int $default = 0): int
  {
    $result = $this->get($path);

    if (null === $result) {
      return $default;
    }

    return intval($result);
  }

  public function getArray(string $path, array $default = []): array
  {
    if (null === ($result = $this->get($path))) {
      return $default;
    }

    return (array)$result;
  }

  public function def(string $path, mixed $default = '')
  {
    $value = $this->get($path, $default);
    $this->set($path, $value);
    return $this;
  }

  public function has(string $path): bool
  {
    return false !== ($this->fetchNode($path));
  }

  public function erase(string $path)
  {
    if ($node = $this->fetchNode($path)) {
      unset($node);
    }
    return $this;
  }

  public function all(): array
  {
    return $this->data;
  }

  private function &fetchNode(string $path): mixed
  {
    $node = &$this->data; // Référence pour modification directe
    $nodes = explode('.', $path); // Découpe la chaîne en segments
    $lastKey = array_pop($nodes); // Récupère la dernière clé séparément

    // Traverse les niveaux du tableau
    foreach ($nodes as $key) {
      if (!isset($node[$key]) || !is_array($node[$key])) {
        return false; // Retourne false si une clé intermédiaire est manquante ou non valide
      }
      $node = &$node[$key];
    }

    // Gestion de la clé finale
    if (isset($node[$lastKey])) {
      return $node[$lastKey]; // Retourne la valeur
    }

    return false; // Retourne false si la clé finale n'existe pas
  }

  /**
   * Flattens a nested array
   *
   * The scheme used is:
   *   'key' => ['key2' => ['key3' => 'value']]
   * Becomes:
   *   'key.key2.key3' => 'value'
   */
  private function flatten(?array $data = null): array
  {
    if (null === $data) {
      $data = $this->data;
    }

    $result = [];
    foreach ($data as $key => $value) {
      if (\is_array($value)) {
        foreach ($this->flatten($value) as $k => $v) {
          if (null !== $v) {
            $result[$key . '.' . $k] = $v;
          }
        }
      } elseif (null !== $value) {
        $result[$key] = $value;
      }
    }

    return $result;
  }

  /**
   * Renders a multidmentional representation of the nested array
   *
   * The scheme used is:
   *   'key.key2.key3' => 'value'
   * Becomes:
   *   'key' => ['key2' => ['key3' => 'value']]
   */
  private function unflatten(?array $data = null): array
  {
    $data = $this->flatten($data);

    $result = [];

    foreach ($data as $flatKey => $value) {
      $keys = explode('.', $flatKey); // Divise la clé par les points
      $current = &$result; // Référence pour construire le tableau multidimensionnel

      foreach ($keys as $key) {
        if (!isset($current[$key]) || !is_array($current[$key])) {
          $current[$key] = []; // Crée une nouvelle sous-structure si elle n'existe pas
        }
        $current = &$current[$key];
      }

      $current = $value; // Assigne la valeur à la dernière clé
    }

    return $result;
  }
}
