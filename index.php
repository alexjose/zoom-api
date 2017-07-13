<?php

require 'vendor/autoload.php';

use Zoom\ZoomApi;

$api_key = "vZ3Bnur5Q1KH-Q395X0dTg";
$api_secret = "Kz4kVk95NvHRNnCJ0ivfO1pRgX1GmI2P7QRd";

$zoomApi = new ZoomApi($api_key, $api_secret);

// $response = $zoomApi->listUsers();

$response = $zoomApi->createMeeting('Rvs4FgQLRTWuwY0Mzt4Epw',1,'Test Meeting 1');

var_dump($response);

// $body = $response->getBody();
// Implicitly cast the body to a string and echo it
// echo $body;