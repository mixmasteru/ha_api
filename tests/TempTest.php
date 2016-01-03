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

    /**
     * @group temp
     */
    public function testGET404()
    {
        $response = $this->client->get('/temp/1/20151201-100000/');

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$response->getStatusCode());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody()->getContents());
    }

    /**
     * @group temp
     */
    public function testGET500()
    {
        $response = $this->client->get('/temp/1/20151201100000/');

        $arr_err_part = array(  "exception" => 'ha\Exception\Parameter',
                                "msg"       => 'no valid date: 20151201100000');

        $this->assertEquals(500, $response->getStatusCode(),"wrong response: ".$response->getStatusCode());
        $arr_resp = json_decode($response->getBody()->getContents(),true);
        $this->assertArraySubset($arr_err_part,$arr_resp,"wrong exception name and msg not: ".$response->getBody()->getContents());
    }

    /**
     * @group temp
     */
    public function testGET500paramtype()
    {
        $datetime   = "20151203-231200";
        $temp       = "s";

        $response = $this->client->put('/temp/1/'.$datetime.'/'.$temp.'/');
        $arr_exception = json_decode($response->getBody()->getContents(),true);

        $this->assertEquals(500, $response->getStatusCode(),"wrong error response code: ".$response->getStatusCode());
        $this->assertEquals("wrong type of parameter: 'temp' must be float", $arr_exception['msg']);
    }

    /**
     * @group temp
     */
    public function testPUT()
    {
        $datetime   = "20151203-231100";
        $temp       = 1.0;
        $json       = '[{"date":"2015-12-03 23:11:00","temp":"1"}]';

        $response = $this->client->put('/temp/1/'.$datetime.'/'.$temp.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->get('/temp/1/'.$datetime.'/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());
    }

    /**
     * @group temp
     */
    public function testDELETE200()
    {
        $datetime   = "20151203-120000";
        $temp       = 1.0;
        $json       = '{"deleted":1}';
        $msg        = 'The server has not found anything matching the Request-URI';

        $response = $this->client->put('/temp/1/'.$datetime.'/'.$temp.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->delete('/temp/1/'.$datetime.'/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());

        //delete again -> nothing to delete
        $response = $this->client->delete('/temp/1/'.$datetime.'/');
        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$response->getStatusCode());
        $this->assertEquals($msg, $response->getBody()->getContents());
    }
}