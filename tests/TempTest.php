<?php
/**
 * Created by IntelliJ IDEA.
 * User: mixmasteru
 * Date: 10.10.15
 * Time: 23:18
 */

namespace ha;

use Guzzle\Http\Client;

/**
 * Class TempTest
 *
 * @package ha
 */
class TempTest extends BaseTest
{

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();
    }

    public function testGET404()
    {
        $request = $this->client->get('/temp/1/20151201-100000/');
        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$request->getResponse());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody());
    }

    public function testPUT()
    {
        $ts     = "20151203-110000";
        $temp   = 1.0;
        $json   = '[{"date":"2015-12-03 11:00:00","temp":"1"}]';

        $request = $this->client->put('/temp/1/'.$ts.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->get('/temp/1/'.$ts.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }
}