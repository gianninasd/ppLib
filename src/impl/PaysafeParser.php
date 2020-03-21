<?php
namespace gianninasd\pplib\impl;

use gianninasd\pplib\ObjectParser;
use gianninasd\pplib\PaymentResponse;
use gianninasd\pplib\PaymentResponseDetails;

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

  /**
   * Parses the given raw JSON object to retrieve detailed response information.
   */
  function parseResponse( PaymentResponse $resp ):PaymentResponseDetails {
    $details = new PaymentResponseDetails();

    if( $resp->curlErrorNo > 0 ) {
      $details->errorNo = "" . $resp->curlErrorNo;
      $details->status = "ERROR";
    }
    else {
      switch( $resp->httpResponseCode ) {
        case 200:
          // update the payment record with some details
          $rez = json_decode($resp->body);
          $details->rawData = $resp->body;
          $details->requestDate = $rez->txnTime;
          $details->status = "COMPLETED";
          $details->id = $rez->id;
          $details->brand = $rez->card->type;
          $details->lastDigits = $rez->card->lastDigits;
          $details->amount = substr($rez->amount, 0, -2); // remove last 2 chars
          $details->authCode = $rez->authCode;
          break;
        default:
          $rez = json_decode($resp->body);
          $details->rawData = $resp->body;
          $details->errorNo = "(" . $resp->httpResponseCode . ") " . $rez->error->code;
          $details->errorDetails = $rez->error->message;
          $details->status = "FAILED";

          if( property_exists($rez, "amount") ) {
            $details->amount = substr($result->amount, 0, -2); // remove last 2 chars
          }

          if( property_exists($rez, "id") ) {
            $details->id = $rez->id;
          }
      }
    }

    return $details;
  }
}