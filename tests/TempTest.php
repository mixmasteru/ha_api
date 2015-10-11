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
 * php server has to run on port 8888 for tests
 * @package ha
 */
class TempTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        // create our http client (Guzzle)
        $this->client = new Client('http://localhost:8888', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));
    }


    public function testGET()
    {
        $request = $this->client->get('/temp/101/');
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
    }
}