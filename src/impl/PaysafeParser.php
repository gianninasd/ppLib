<?php
namespace gianninasd\pplib\impl;

use gianninasd\pplib\ObjectParser;

/**
 * 
 */
class PaysafeParser implements ObjectParser {

  /**
   * Returns an object that will be used to create our JSON content for the payment request.
   */
  function parseRequest( string $uuid, string $paymentToken, object $member, string $amountOwed ) {
    // create anonymous class objects
    $billingDetails = new class {
      public $country = "";
      public $state = "";
      public $zip = "";
      public $city = "";
      public $street = "";
      public $street2 = "";
    };
    $billingDetails->country = $member->country;
    $billingDetails->state = $member->province;
    $billingDetails->zip = $member->postalCode;
    $billingDetails->city = $member->city;
    $billingDetails->street = $member->street1;
    $billingDetails->street2 = $member->street2;

    $profile = new class {
      public $firstName = "";
      public $lastName = "";
      public $email = "";
    };
    $profile->firstName = $member->firstName;
    $profile->lastName = $member->lastName;
    $profile->email = $member->email;

    $card = new class {
      public $paymentToken = "";
    };
    $card->paymentToken = $paymentToken;

    $cardReq = new class {
      public $merchantRefNum = "";
      public $amount;
      public $settleWithAuth = true;
      public $card;
      public $profile;
      public $billingDetails;
    };

    // generate the full request object
    $cardReq->merchantRefNum = $uuid;
    $cardReq->amount = $amountOwed;
    $cardReq->card = $card;
    $cardReq->profile = $profile;
    $cardReq->billingDetails = $billingDetails;

    return $cardReq;
  }

  function parseResponse() {
    
  }
}