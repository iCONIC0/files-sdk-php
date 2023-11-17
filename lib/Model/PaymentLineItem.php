<?php

declare(strict_types=1);

namespace Files\Model;

use Files\Api;
use Files\Files;
use Files\Logger;

require_once __DIR__ . '/../Files.php';

/**
 * Class PaymentLineItem
 *
 * @package Files
 */
class PaymentLineItem {
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

  // double # Payment line item amount
  public function getAmount() {
    return @$this->attributes['amount'];
  }

  // date-time # Payment line item created at date/time
  public function getCreatedAt() {
    return @$this->attributes['created_at'];
  }

  // int64 # Invoice ID
  public function getInvoiceId() {
    return @$this->attributes['invoice_id'];
  }

  // int64 # Payment ID
  public function getPaymentId() {
    return @$this->attributes['payment_id'];
  }
}
