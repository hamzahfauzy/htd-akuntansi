<?php

$_POST[$table]['is_active'] = 'TIDAK';
$_POST[$table]['is_open'] = 'BUKA';

if(empty($_POST[$table]['ref_id']))
{
    unset($_POST[$table]['ref_id']);
}