<?php
namespace gianninasd\pplib;

use gianninasd\pplib\PaymentRequest;

class PaymentService {

  /**
   * Process the payment request
   */
  function process( PaymentRequest $request ) {
    /* $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->cardUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: Basic ' . $this->paymentApiKey) );
    $result = curl_exec($ch);
    $errNo = curl_errno($ch);
    $errDetails = curl_error($ch);
    $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    error_log($uuid . " Response(" . $httpResponseCode . "): " . $errNo . " (" . $errDetails . ") - body: " . $result);
    curl_close($ch); */
  }
}