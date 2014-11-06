<?php

// Tuning components for the module dependencies BackEnd

// Component URL is used to generate all kinds of addresses in the annex

$di->set('url', function() {

    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($this->_config->application->baseUri);
    return $url;

});

// Database connection is created based in the parameters defined in the configuration file

$di->setShared('db', function() {

    return new \Phalcon\Db\Adapter\Pdo\Mysql([
        "host"          => 	$this->_config->database->host,
        "username"      => 	$this->_config->database->username,
        "password"      => 	$this->_config->database->password,
        "dbname"        => 	$this->_config->database->dbname,
        "persistent"    => 	$this->_config->database->persistent,
        "options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$this->_config->database->charset}'",
            PDO::ATTR_CASE 		=> PDO::CASE_LOWER,
            PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        )
    ]);
});

// Component Session. Starting a Session
$di->setShared('session', function() {

    $session = new Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;

});

