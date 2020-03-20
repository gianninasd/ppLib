<?php
namespace gianninasd\pplib\impl;

use gianninasd\pplib\PaymentService;
use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\PaymentResponse;

/**
 * 
 */
class PaysafePaymentService implements PaymentService {

  /**
   * Process the payment request
   */
  function process( PaymentRequest $request ):PaymentResponse {
    try {
      error_log($request->uuid . " Sending payment for " . $request->id);

      $ch = curl_init();
      error_log("0011>");
      curl_setopt($ch, CURLOPT_URL, $request->url);
      error_log("00112>");
      curl_setopt($ch, CURLOPT_POST, true);
      error_log("00113>");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request->body);
      error_log("00114>");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      error_log("00115>");
      curl_setopt($ch, CURLOPT_VERBOSE, true);
      error_log("00116>");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      error_log("00117>");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: Basic ' . $request->authenticationToken) );

      error_log("11>");

      $resp = new PaymentResponse();
      $resp->uuid = $request->uuid;
      $resp->body = curl_exec($ch);
      $resp->curlErrorNo = curl_errno($ch);
      $resp->curlErrorDetails = curl_error($ch);
      $resp->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      error_log($resp->uuid . " Response(" . $resp->httpResponseCode . "): " . $resp->curlErrorNo . " (" . $resp->curlErrorDetails . ") - body: " . $resp->body);
      error_log("22>");
      curl_close($ch);
      error_log("33>");

      error_log($resp);

      /*$payment = $this->parser->parse( $errNo, $httpResponseCode, $result );
      error_log($uuid . " Parsed response " . $payment->status);*/
      return $resp;
    }
    catch ( Exception $ex ) {
      error_log("Unable to process payment: " . $ex );
      throw $ex;
    }
  }
}