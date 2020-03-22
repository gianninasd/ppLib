<?php
namespace gianninasd\pplib;

/**
 * Domain object representing the raw request to be submitted for a payment.
 */
class PaymentRequest {

  /**
   * Id that is used internal to identify this request
   */
  public $id;

  /**
   * Unique identifier for this payment request
   */
  public $uuid;

  /**
   * URL where the payment request will be sent
   */
  public $url;

  /**
   * Authentication token used as part of the HTTP Post request
   */
  public $authenticationToken;  

  /**
   * The full body of the request to be sent in JSON format
   */
  public $body;

}