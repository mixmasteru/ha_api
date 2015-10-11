<?php
namespace ha;

use ha\Exception\Database as DatabaseException;

/**
 * Created by IntelliJ IDEA.
 * User: mixmasteru
 * Date: 07.10.15
 * Time: 19:51
 */
class BaseResource extends \Tonic\Resource
{
    /**
     * @var Pimple
     */
    public $container;
    
    /**
     * 
     * @param string $database
     * @throws Tonic\NotFoundException
     * @return \PDO
     */
    protected function getDB($database)
    {
    	$dsn = sprintf($this->container['db_config']['dsn'], $database);
    	try
		{
    		return new \PDO($dsn, $this->container['db_config']['username'], $this->container['db_config']['password']);
    	} catch (\Exception $e) {
    		throw new DatabaseException("db error", 0, $e);
    	}
    }
}