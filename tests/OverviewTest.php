<?php
namespace ha;


class OverviewTest extends BaseTest
{
    public function testOverview()
    {
        $request = $this->client->get('/');
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode(array("welcome")), $response->getBody(),"error: ".$response->getBody());
    }
}