<?php

require 'header.php';

$host_id = "";

$response = $zoomApi->createMeeting($host_id, 1, 'Test Meeting');

var_dump($response);
