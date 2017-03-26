<?php
/**
 * Created by PhpStorm.
 * User: iRam
 * Date: 26/3/17
 * Time: 12:31 PM
 */
header('Content-type: application/json');

$payload = array(
    'status' => false,
    'error' => $data,
);

if (!isset($code))
    $code = 400;

http_response_code($code);
echo json_encode($payload, JSON_NUMERIC_CHECK);
exit;
