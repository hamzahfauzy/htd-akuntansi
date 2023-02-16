<?php

$fields['username'] = [
    'label' => 'Username',
    'type'  => 'text'
];

$fields['password'] = [
    'label' => 'Password',
    'type'  => 'password'
];

$fields['role'] = [
    'label' => 'Role',
    'type'  => 'options-obj:roles,id,name'
];

return $fields;