<?php

$fields['username'] = [
    'label' => 'Username',
    'type'  => 'text'
];

$fields['password'] = [
    'label' => 'Password',
    'type'  => 'password'
];

$fields['group'] = [
    'label' => 'Group',
    'type'  => 'options-obj:groups,id,name'
];

$fields['role'] = [
    'label' => 'Role',
    'type'  => 'options-obj:roles,id,name'
];

return $fields;