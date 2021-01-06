<?php

declare(strict_types=1);

namespace Files\Model;

use Files\Api;
use Files\Logger;

require_once __DIR__ . '/../Files.php';

/**
 * Class BundleDownload
 *
 * @package Files
 */
class BundleDownload {
  private $attributes = [];
  private $options = [];

  function __construct($attributes = [], $options = []) {
    foreach ($attributes as $key => $value) {
      $this->attributes[str_replace('?', '', $key)] = $value;
    }

    $this->options = $options;
  }

  public function __get($name) {
    return @$this->attributes[$name];
  }

  public function isLoaded() {
    return !!@$this->attributes['id'];
  }

  public function getBundleRegistration() {
    return @$this->attributes['bundle_registration'];
  }

  // string # Download method (file or full_zip)
  public function getDownloadMethod() {
    return @$this->attributes['download_method'];
  }

  // string # Download path This must be slash-delimited, but it must neither start nor end with a slash. Maximum of 5000 characters.
  public function getPath() {
    return @$this->attributes['path'];
  }

  // date-time # Download date/time
  public function getCreatedAt() {
    return @$this->attributes['created_at'];
  }

  // Parameters:
  //   cursor - string - Used for pagination.  Send a cursor value to resume an existing list from the point at which you left off.  Get a cursor from an existing list via the X-Files-Cursor-Next header.
  //   per_page - int64 - Number of records to show per page.  (Max: 10,000, 1,000 or less is recommended).
  //   bundle_id - int64 - Bundle ID
  //   bundle_registration_id - int64 - BundleRegistration ID
  public static function list($params = [], $options = []) {
    if (@$params['cursor'] && !is_string(@$params['cursor'])) {
      throw new \InvalidArgumentException('Bad parameter: $cursor must be of type string; received ' . gettype($cursor));
    }

    if (@$params['per_page'] && !is_int(@$params['per_page'])) {
      throw new \InvalidArgumentException('Bad parameter: $per_page must be of type int; received ' . gettype($per_page));
    }

    if (@$params['bundle_id'] && !is_int(@$params['bundle_id'])) {
      throw new \InvalidArgumentException('Bad parameter: $bundle_id must be of type int; received ' . gettype($bundle_id));
    }

    if (@$params['bundle_registration_id'] && !is_int(@$params['bundle_registration_id'])) {
      throw new \InvalidArgumentException('Bad parameter: $bundle_registration_id must be of type int; received ' . gettype($bundle_registration_id));
    }

    $response = Api::sendRequest('/bundle_downloads', 'GET', $params, $options);

    $return_array = [];

    foreach ($response->data as $obj) {
      $return_array[] = new BundleDownload((array)$obj, $options);
    }

    return $return_array;
  }

  public static function all($params = [], $options = []) {
    return self::list($params, $options);
  }
}
