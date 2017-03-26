<?php
/**
 * Created by PhpStorm.
 * User: iRam
 * Date: 26/3/17
 * Time: 12:28 PM
 */
header('Content-type: application/json');

$payload = array(
    'status' => true
);

echo json_encode(array_merge($payload, $data));
exit;