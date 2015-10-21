<?php
namespace ha;


class OverviewTest extends BaseTest
{
    public function testGET404()
    {
        $request = $this->client->get('/');
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode(array("welcome")), $response->getBody());
    }
}