<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

// $isApiV1 = startWith($route, 'api/v1/');
$nonAuthRoute = !in_array($route,[
    'api/bills/lists',
    'api/bills/unpayment',
    'api/subjects/lists',
]);

if($nonAuthRoute)
{
    // check if token is sent
    $token = getBearerToken();
    if(!$token)
    {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Api key is required'
        ]);
        die();
    }

    // check if user exists in token
    if($token != config('api_token'))
    {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Api key is not valid'
        ]);
        die();
    }
}

return true;