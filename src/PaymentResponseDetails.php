<?php
namespace gianninasd\pplib;

use \DateTimeZone;
use \DateTime;
use \DateInterval;

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

  /**
   * Returns the requestDate in the specified timezone, default to America/New_York
   */
  public function displayLocalRequestDate( $tz = 'America/New_York' ) {
    $userTimezone = new DateTimeZone($tz);
    $gmtTimezone = new DateTimeZone('UTC');
    $myDateTime = new DateTime($this->requestDate, $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    $myInterval = DateInterval::createFromDateString((string)$offset . 'seconds');
    $myDateTime->add($myInterval);
    return $myDateTime->format('Y-m-d H:i:s');
  }

  public function __toString() {
    return "{$this->status} {$this->errorNo}: {$this->errorDetails}";
  }
}