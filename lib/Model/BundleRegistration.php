<?php

declare(strict_types=1);

namespace Files\Model;

use Files\Api;
use Files\Files;
use Files\Logger;

require_once __DIR__ . '/../Files.php';

/**
 * Class BundleRegistration
 *
 * @package Files
 */
class BundleRegistration {
  private $attributes = [];
  private $options = [];
  private static $static_mapped_functions = [
    'list' => 'all',
  ];

  function __construct($attributes = [], $options = []) {
    foreach ($attributes as $key => $value) {
      $this->attributes[str_replace('?', '', $key)] = $value;
    }

    $this->options = $options;
  }

  public function __set($name, $value) {
    $this->attributes[$name] = $value;
  }

  public function __get($name) {
    return @$this->attributes[$name];
  }

  public static function __callStatic($name, $arguments) {
    if(in_array($name, array_keys(self::$static_mapped_functions))){
      $method = self::$static_mapped_functions[$name];
      if (method_exists(__CLASS__, $method)){
        return @self::$method($arguments);
      }
    }
  }

  public function isLoaded() {
    return !!@$this->attributes['id'];
  }

  // string # Registration cookie code
  public function getCode() {
    return @$this->attributes['code'];
  }

  // string # Registrant name
  public function getName() {
    return @$this->attributes['name'];
  }

  // string # Registrant company name
  public function getCompany() {
    return @$this->attributes['company'];
  }

  // string # Registrant email address
  public function getEmail() {
    return @$this->attributes['email'];
  }

  // string # Registrant IP Address
  public function getIp() {
    return @$this->attributes['ip'];
  }

  // string # InboxRegistration cookie code, if there is an associated InboxRegistration
  public function getInboxCode() {
    return @$this->attributes['inbox_code'];
  }

  // string # Clickwrap text that was shown to the registrant
  public function getClickwrapBody() {
    return @$this->attributes['clickwrap_body'];
  }

  // int64 # Id of associated form field set
  public function getFormFieldSetId() {
    return @$this->attributes['form_field_set_id'];
  }

  // object # Data for form field set with form field ids as keys and user data as values
  public function getFormFieldData() {
    return @$this->attributes['form_field_data'];
  }

  // string # Bundle URL code
  public function getBundleCode() {
    return @$this->attributes['bundle_code'];
  }

  // int64 # Id of associated bundle
  public function getBundleId() {
    return @$this->attributes['bundle_id'];
  }

  // int64 # Id of associated bundle recipient
  public function getBundleRecipientId() {
    return @$this->attributes['bundle_recipient_id'];
  }

  // date-time # Registration creation date/time
  public function getCreatedAt() {
    return @$this->attributes['created_at'];
  }

  // Parameters:
  //   cursor - string - Used for pagination.  When a list request has more records available, cursors are provided in the response headers `X-Files-Cursor-Next` and `X-Files-Cursor-Prev`.  Send one of those cursor value here to resume an existing list from the next available record.  Note: many of our SDKs have iterator methods that will automatically handle cursor-based pagination.
  //   per_page - int64 - Number of records to show per page.  (Max: 10,000, 1,000 or less is recommended).
  //   bundle_id - int64 - ID of the associated Bundle
  public static function all($params = [], $options = []) {
    if (@$params['cursor'] && !is_string(@$params['cursor'])) {
      throw new \Files\InvalidParameterException('$cursor must be of type string; received ' . gettype(@$params['cursor']));
    }

    if (@$params['per_page'] && !is_int(@$params['per_page'])) {
      throw new \Files\InvalidParameterException('$per_page must be of type int; received ' . gettype(@$params['per_page']));
    }

    if (@$params['bundle_id'] && !is_int(@$params['bundle_id'])) {
      throw new \Files\InvalidParameterException('$bundle_id must be of type int; received ' . gettype(@$params['bundle_id']));
    }

    $response = Api::sendRequest('/bundle_registrations', 'GET', $params, $options);

    $return_array = [];

    foreach ($response->data as $obj) {
      $return_array[] = new BundleRegistration((array)$obj, $options);
    }

    return $return_array;
  }



}
