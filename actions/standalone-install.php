<?php

$parent_path = '../';
if (in_array(php_sapi_name(),["cli","cgi-fcgi"])) {
    $parent_path = '';
}

$conn  = conn();
$db    = new Database($conn);

$data = [
    'name' => config('app_name'),
    'email' => config('app_email'),
    'address' => config('app_address'),
    'phone' => config('app_phone'),
];

// save application installation
$db->insert('application',$data);

// create user login
$userdata = [];
$userdata['name'] = "Admin ".$data['name'];
$userdata['username'] = 'admin';
$userdata['password'] = md5('admin');
$user = $db->insert('users', $userdata);

// create roles
$role = $db->insert('roles',[
    'name' => 'administrator'
]);

// assign role to user
$db->insert('user_roles',[
    'user_id' => $user->id,
    'role_id' => $role->id
]);

// create roles route
$role = $db->insert('role_routes',[
    'role_id' => $role->id,
    'route_path' => '*'
]);

echo "Installation Success";