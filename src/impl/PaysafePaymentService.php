<?php
namespace gianninasd\pplib\impl;

use gianninasd\pplib\PaymentService;
use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\PaymentResponse;

/**
 * 
 */
class PaysafePaymentService implements PaymentService {

  public $verbose;

  function __construct( $verbose ) {
    $this->verbose = $verbose;
  }

  /**
   * Process the payment request
   */
  function process( PaymentRequest $request ):PaymentResponse {
    try {
      error_log($request->uuid . " Sending payment for " . $request->id);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $request->url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request->body);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_VERBOSE, $this->verbose);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: Basic ' . $request->authenticationToken) );

      $resp = new PaymentResponse( $request->uuid );
      $resp->body = curl_exec($ch);
      $resp->curlErrorNo = curl_errno($ch);
      $resp->curlErrorDetails = curl_error($ch);
      $resp->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      error_log($resp);
      curl_close($ch);

      return $resp;
    }
    catch ( Exception $ex ) {
      error_log("Unable to process payment: " . $ex );
      throw $ex;
    }
  }
}