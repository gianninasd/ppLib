<?php
use Codeception\Test\Unit;
use gianninasd\pplib\PaymentResponseDetails;

/**
 * Unit test for the PaymentResponseDetails class
 */
class PaymentResponseDetailsTest extends Unit {

  protected function _before() {
  }

  public function testNYCDisplayLocalRequestDate() {
    $details = new PaymentResponseDetails();
    $details->requestDate = "2020-04-04T21:30:06Z";

    $this->assertSame("2020-04-04 17:30:06", $details->displayLocalRequestDate());
  }
}