<?php
namespace gianninasd\pplib;

/**
 * 
 */
class PaymentResponse {

  public $uuid;
  public $curlErrorNo;
  public $curlErrorDetails;
  public $httpResponseCode;  
  public $body;

  function __construct( $uuid ) {
    $this->uuid = $uuid;
  }

  public function __toString() {
    return $this->uuid . " Response(" . $this->httpResponseCode . "): " . $this->curlErrorNo . " (" . $this->curlErrorDetails . ") - body: " . $this->body;
  }
}