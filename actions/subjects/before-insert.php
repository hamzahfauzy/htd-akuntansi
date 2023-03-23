<?php

$name     = $_POST[$table]['name'];
$username = $_POST[$table]['username'];
$password = md5($_POST[$table]['password']);
$role_id  = $_POST[$table]['role'];

unset($_POST[$table]['username']);
unset($_POST[$table]['password']);
unset($_POST[$table]['role']);

$user = $db->insert('users',[
    'name' => $name,
    'username' => $username,
    'password' => $password,
]);

$db->insert('user_roles',[
    'user_id' => $user->id,
    'role_id' => $role_id
]);

$_POST[$table]['user_id'] = $user->id;

$_POST['group_id'] = $_POST[$table]['group'];

unset($_POST[$table]['group']);
