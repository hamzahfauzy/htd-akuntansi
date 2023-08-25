<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

// $isApiV1 = startWith($route, 'api/v1/');
$nonAuthRoute = in_array($route,[
    'api/accounts/lists',
    'api/bills/lists',
    'api/bills/single-unpayment',
    'api/bills/unpayment',
    'api/subjects/lists',
    'api/test/send-wa',
    'api/test/send-wa-queue',
]);

if($nonAuthRoute)
{
    return true;
}

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
$userExists = $db->exists('users',['auth_token' => $token]);
if(!$userExists)
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Api key is not valid'
    ]);
    die();
}

ApiSession::set($token);

$auth = auth('api');
// check if route is allowed
if(!is_allowed($route, $auth->user->id))
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    die();
}

return true;