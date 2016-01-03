<?php
namespace ha;


class TempsTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @group temps
     */
    public function testGET404()
    {
        $msg = 'The server has not found anything matching the Request-URI';
        $response = $this->client->get('/temps/2/10/10/');

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$response->getStatusCode());
        $this->assertEquals($msg, $response->getBody()->getContents());
    }

    /**
     * @group temps
     */
    public function testGET200()
    {
        $datetime1  = "20151201-110000";
        $datetime2  = "20151201-120000";
        $temp       = 1.0;
        $json       = '[{"date":"2015-12-01 12:00:00","temp":"1"},{"date":"2015-12-01 11:00:00","temp":"1"}]';

        $response = $this->client->put('/temp/2/'.$datetime1.'/'.$temp.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->put('/temp/2/'.$datetime2.'/'.$temp.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->get('/temps/2/0/10/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());
    }


}