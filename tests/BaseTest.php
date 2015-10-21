<?php
namespace ha;
use Guzzle\Service\Client;

/**
 * Class BaseTest
 * php server has to run on port 3148 for tests
 *
 * @package ha
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    const TESTHOST = 'http://localhost:3148';

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        // create our http client (Guzzle)
        $this->client = new Client(self::TESTHOST, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));
    }

}