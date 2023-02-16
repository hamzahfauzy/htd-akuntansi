<?php

$_POST[$table]['report_id'] = activeMaster()->id;

if(empty($_POST[$table]['parent_id']))
{
    unset($_POST[$table]['parent_id']);
}