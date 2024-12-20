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

    foreach ($nodes as $key) {
      // Si la clé est numérique, on force la conversion en entier
      $key = is_numeric($key) ? (int)$key : $key;

      // Si le nœud n'existe pas ou n'est pas conforme, on initialise
      if (!isset($node[$key]) || (!is_array($node[$key]) && $key !== (int)$key)) {
        $node[$key] = [];
      }

      $node = &$node[$key]; // Passe au niveau suivant
    }

    // Assigne la valeur au nœud final
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
      // Gestion des clés numériques
      $key = is_numeric($key) ? (int)$key : $key;

      if (is_array($value)) {
        // Appel récursif pour les sous-niveaux
        foreach ($this->flatten($value, $separator) as $subKey => $subValue) {
          $result[$key . $separator . $subKey] = $subValue;
        }
      } elseif (null !== $value) {
        // Ajout direct si la valeur n'est pas un tableau
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
  protected function unflatten(?array $data = null): array
  {
    $data = $this->flatten($data); // Aplatit les données pour garantir un traitement cohérent
    $result = [];

    foreach ($data as $flatKey => $value) {
      $keys = explode('.', $flatKey); // Divise la clé par les points
      $current = &$result; // Référence pour construire le tableau multidimensionnel

      foreach ($keys as $key) {
        // Gestion des clés numériques
        $key = is_numeric($key) ? (int)$key : $key;

        if (!isset($current[$key]) || !is_array($current[$key])) {
          $current[$key] = []; // Crée une nouvelle sous-structure si elle n'existe pas
        }
        $current = &$current[$key];
      }

      $current = $value; // Assigne la valeur à la dernière clé
    }

    return $result;
  }

  private function &fetchNode(string $path): mixed
  {
    $node = &$this->data; // Référence pour modification directe
    $nodes = explode('.', $path); // Découpe la chaîne en segments
    $lastKey = array_pop($nodes); // Récupère la dernière clé séparément

    // Traverse les niveaux du tableau
    foreach ($nodes as $key) {
      // Gestion des clés numériques
      $key = is_numeric($key) ? (int)$key : $key;

      if (!isset($node[$key]) || !is_array($node[$key])) {
        $null = null; // Pour gérer la référence dans un cas d'échec
        return $null; // Retourne une référence nulle si une clé intermédiaire est manquante
      }
      $node = &$node[$key]; // Descend d'un niveau
    }

    // Gestion de la clé finale
    $lastKey = is_numeric($lastKey) ? (int)$lastKey : $lastKey;
    if (isset($node[$lastKey])) {
      return $node[$lastKey]; // Retourne la référence à la valeur
    }

    $null = null; // Retourne une référence nulle si la clé finale n'existe pas
    return $null;
  }
}
