<?php
use Codeception\Test\Unit;
use gianninasd\pplib\PaymentRequest;
use gianninasd\pplib\impl\PaysafeParser;
use gianninasd\pplib\impl\PaysafePaymentService;

/**
 * Unit test for the PaysafePaymentService class
 */
class PaysafePaymentServiceTest extends Unit {

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

  private function createGoodBody( $uuid ) {
    $token = "cgOSYqmX1W";
    $amt = "1500";

    $parser = new PaysafeParser();
    $obj = $parser->parseRequest( $uuid, $token, $this->member, $amt );
    return json_encode($obj, JSON_NUMERIC_CHECK);
  }

  private function createGoodRequest( $uuid, $body ) {
    $req = new PaymentRequest();
    $req->id = "rick@sdf3.com";
    $req->uuid = $uuid;
    $req->body = $body;
    return $req;
  }

  public function testBadToken() {
    $uuid = uniqid("", true);
    $body = $this->createGoodBody( $uuid );
    $req = $this->createGoodRequest( $uuid, $body );
    
    $ps = new PaysafePaymentService( __URL__, __AUTHTOKEN__, false );
    $resp = $ps->process($req);
    $this->assertSame($uuid, $resp->uuid);
    $this->assertSame(400, $resp->httpResponseCode);
    $this->assertStringStartsWith("{", $resp->body);
  }

  public function testBadKey() {
    $uuid = uniqid("", true);
    $body = $this->createGoodBody( $uuid );
    $req = $this->createGoodRequest( $uuid, $body );
    
    $ps = new PaysafePaymentService( __URL__, "xxx", false );
    $resp = $ps->process($req);
    $this->assertSame($uuid, $resp->uuid);
    $this->assertSame(401, $resp->httpResponseCode);
    $this->assertStringStartsWith("{", $resp->body);
  }
}