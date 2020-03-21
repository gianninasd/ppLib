<?php
use Codeception\Test\Unit;
use gianninasd\pplib\ObjectParser;
use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\PaymentResponse;
use gianninasd\pplib\impl\PaysafeParser;

/**
 * Unit test for the PaysafeParser class
 */
class PaysafeParserTest extends Unit {

  private $member;

  protected function _before() {
    $this->member = new class {
      public $orgId;
      public $id;
      public $title;
      public $firstName = "John";
      public $lastName = "Doe";
      public $statusCode;
      public $memberType;
      public $email = "john@sdf3.com";
      public $homePhone;
      public $cellPhone;
      public $street1 = "123 Broadway";
      public $street2 = "";
      public $city = "Montreal";
      public $country = "CA";
      public $province = "QC";
      public $postalCode = "H8P1K1";
    };
  }

  public function testParseRequest() {
    $uuid = "uuid1";
    $token = "token1";
    $amt = "1500";

    $parser = new PaysafeParser();
    $obj = $parser->parseRequest( "uuid1", "token1", $this->member, "1500" );
    $this->assertSame($uuid, $obj->merchantRefNum);
    $this->assertSame($token, $obj->card->paymentToken);
    $this->assertSame($amt, $obj->amount);
    $this->assertSame($this->member->postalCode, $obj->billingDetails->zip);
    $this->assertSame($this->member->email, $obj->profile->email);
  }

  public function testGoodParseResponse() {
    $uuid = "uuid1";
    $resp = new PaymentResponse( $uuid );
    $resp->curlErrorNo = 0;
    $resp->curlErrorDetails = "";
    $resp->httpResponseCode = 200;
    $resp->body = '{"id": "17b862a6-c6ac-43a7-87fb-7a6e32af4a55",
      "merchantRefNum": "5e511d3494c137.98836110",
      "txnTime": "2020-02-22T12:23:16Z",
      "status": "COMPLETED",
      "amount": 10000,
      "settleWithAuth": true,
      "preAuth": false,
      "availableToSettle": 0,
      "card": {
        "type": "VI",
        "lastDigits": "1111",
        "cardExpiry": {
          "month": 10,
          "year": 2020
        }
      },
      "authCode": "144759",
      "profile": {
        "firstName": "John",
        "lastName": "Doe",
        "email": "john@gmail.com"
      },
      "billingDetails": {
        "street": "123 Broadway",
        "city": "Veria",
        "country": "GR",
        "zip": "59100"
      }}';

    $parser = new PaysafeParser();
    $obj = $parser->parseResponse( $resp );
    $this->assertSame("COMPLETED", $obj->status);
    $this->assertSame("2020-02-22T12:23:16Z", $obj->requestDate);
    $this->assertSame("144759", $obj->authCode);
    $this->assertSame("VI", $obj->brand);
    $this->assertSame("100", $obj->amount);
  }

  public function test401ParseResponse() {
    $uuid = "uuid1";
    $resp = new PaymentResponse( $uuid );
    $resp->curlErrorNo = 0;
    $resp->curlErrorDetails = "";
    $resp->httpResponseCode = 401;
    $resp->body = '{"id":"98a3cd89-c7f2-4895-8dd9-4006df967c7f",
      "error":{"code":"5500","message":"Either the payment token is invalid or the corresponding profile or credit card is not active."}}';

    $parser = new PaysafeParser();
    $obj = $parser->parseResponse( $resp );
    $this->assertSame("98a3cd89-c7f2-4895-8dd9-4006df967c7f", $obj->id);
    $this->assertSame("FAILED", $obj->status);
    $this->assertSame($resp->body, $obj->rawData);
    $this->assertSame("Either the payment token is invalid or the corresponding profile or credit card is not active.", $obj->errorDetails);
    $this->assertSame("(401) 5500", $obj->errorNo);
  }

  public function test400ParseResponse() {
    $uuid = "uuid1";
    $resp = new PaymentResponse( $uuid );
    $resp->curlErrorNo = 0;
    $resp->curlErrorDetails = "";
    $resp->httpResponseCode = 400;
    $resp->body = '{"error": {"code": "5279","message": "The authentication credentials are invalid."}}';

    $parser = new PaysafeParser();
    $obj = $parser->parseResponse( $resp );
    $this->assertNull($obj->id);
    $this->assertSame("FAILED", $obj->status);
    $this->assertSame($resp->body, $obj->rawData);
    $this->assertSame("The authentication credentials are invalid.", $obj->errorDetails);
    $this->assertSame("(400) 5279", $obj->errorNo);
  }
}