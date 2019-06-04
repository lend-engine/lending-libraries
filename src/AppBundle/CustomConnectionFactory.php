<?php

/**
 * Connect
 */

namespace AppBundle;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Component\HttpFoundation\Session\Session;

class CustomConnectionFactory extends ConnectionFactory
{

    private $server;
    private $database;
    private $username;
    private $password;

    function __construct(Session $session)
    {

        $this->session = $session;

        $url = getenv('RDS_URL');

        if ($url) {
            $dbParts        = parse_url($url);
            $this->server   = $dbParts['host'];
            $this->username = $dbParts['user'];
            $this->password = $dbParts['pass'];
            $this->database = '_directory';
        } else {
            $this->server   = '127.0.0.1';
            $this->username = getenv('DEV_DB_USER');
            $this->password = getenv('DEV_DB_PASS');
            $this->database = '_directory';
        }

        if (!$this->server || !$this->username) {
            die("Could not get DB details from ENV variables (env=".getenv('SYMFONY_ENV').").");
        }

    }

    public function createConnection(array $params, Configuration $config = null, EventManager $eventManager = null, array $mappingTypes = array())
    {

        $params['driver']   = 'pdo_mysql';
        $params['host']     = $this->server;
        $params['port']     = 3306;
        $params['dbname']   = $this->database;
        $params['user']     = $this->username;
        $params['password'] = $this->password;

        //continue with regular connection creation using new params
        return parent::createConnection($params, $config, $eventManager,$mappingTypes);
    }

}