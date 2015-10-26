<?php
namespace ha;


class TempsTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGET404()
    {
        $request = $this->client->get('/temps/2/10/10/');
        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$request->getResponse());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody());
    }

    public function testGET200()
    {
        $ts1     = "20151201-110000";
        $ts2     = "20151201-120000";
        $temp   = 1.0;
        $json   = '[{"date":"2015-12-01 11:00:00","temp":"1"},{"date":"2015-12-01 12:00:00","temp":"1"}]';

        $request = $this->client->put('/temp/2/'.$ts1.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->put('/temp/2/'.$ts2.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->get('/temps/2/0/10/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }


}