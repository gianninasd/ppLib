<?php
namespace gianninasd\pplib;

use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\PaymentResponse;

/**
 * 
 */
interface PaymentService {

  /**
   * Process the payment request
   */
  function process( PaymentRequest $request ):PaymentResponse;
}