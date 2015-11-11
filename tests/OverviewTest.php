<?php
namespace ha;


class OverviewTest extends BaseTest
{
    public function testOverview()
    {
        $request = $this->client->get('/');
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody(),"empty body ");
    }
}