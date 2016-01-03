<?php
namespace ha;


class OverviewTest extends BaseTest
{
    /**
     * @group overview
     */
    public function testOverview()
    {
        $response = $this->client->get('/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody(),"empty body ");
    } 
}