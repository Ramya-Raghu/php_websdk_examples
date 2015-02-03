<?php

    require_once 'plivo.php';

    $auth_id = "Your AUTH_ID";
    $auth_token = "Your AUTH_TOKEN";

    $endpoint_id = $_GET['endpoint_id'];
    $p = new RestAPI($auth_id, $auth_token);
    $params = array(
        'endpoint_id' => $endpoint_id
    );

    $response = $p->delete_endpoint($params);
    return "Deleted";

?>