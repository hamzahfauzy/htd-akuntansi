<?php

$_POST['group_id'] = $_POST[$table]['group'];

unset($_POST[$table]['group']);

if(empty($_POST[$table]['parent_id']) || $_POST[$table]['parent_id'] == '')
{
    unset($_POST[$table]['parent_id']);
}