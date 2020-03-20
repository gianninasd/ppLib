<?php
use Codeception\Test\Unit;
use gianninasd\pplib\ObjectParser;
use gianninasd\pplib\PaymentRequest;
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
}