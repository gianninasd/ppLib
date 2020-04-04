<?php
namespace gianninasd\pplib;

use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\PaymentResponse;

/**
 * Interface to be implemented to interact with payment processing vendor.
 */
interface PaymentService {

  /**
   * Process the payment request
   */
  function process( PaymentRequest $request ):PaymentResponse;
}