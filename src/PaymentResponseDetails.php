<?php
namespace gianninasd\pplib;

/**
 * Domain object representing the response details from a submitted payment.
 */
class PaymentResponseDetails {

  public $id;
  public $requestDate;
  public $brand;
  public $lastDigits;
  public $amount;
  public $authCode;
  public $status;
  public $errorNo;
  public $errorDetails;
  public $rawData;

  public function __toString() {
    return "{$this->status} {$this->errorNo}: {$this->errorDetails}";
  }
}