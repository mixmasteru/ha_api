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

    public function testGET500()
    {
        $request = $this->client->get('/temp/1/20151201100000/');
        $response = $request->send();

        $arr_err_part = array(  "exception" => 'ha\Exception\Parameter',
                                "msg"       => 'no valid date: 20151201100000');

        $this->assertEquals(500, $response->getStatusCode(),"wrong response: ".$request->getResponse());
        $arr_resp = json_decode($response->getBody(true),true);
        $this->assertArraySubset($arr_err_part,$arr_resp,"wrong exception name and msg not: ".$response->getBody());
    }

    public function testPUT()
    {
        $datetime   = "20151203-231100";
        $temp       = 1.0;
        $json       = '[{"date":"2015-12-03 23:11:00","temp":"1"}]';

        $request = $this->client->put('/temp/1/'.$datetime.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->get('/temp/1/'.$datetime.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }

    public function testDELETE200()
    {
        $datetime   = "20151203-120000";
        $temp       = 1.0;
        $json       = '{"deleted":1}';

        $request = $this->client->put('/temp/1/'.$datetime.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->delete('/temp/1/'.$datetime.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }
}