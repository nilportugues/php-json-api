<?php
include_once "../../vendor/autoload.php";

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

//Build Request
$request = ServerRequestFactory::fromGlobals();
$response = new Response();