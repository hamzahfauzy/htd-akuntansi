<?php

$_POST[$table]['report_id'] = activeMaster()->id;
if(isset($_GET['parent_id']) && !empty($_GET['parent_id']))
{
    $_POST[$table]['parent_id'] = $_GET['parent_id'];
}

if(empty($_POST[$table]['balance_amount']))
{
    unset($_POST[$table]['balance_amount']);
}

if(empty($_POST[$table]['budget_amount']))
{
    unset($_POST[$table]['budget_amount']);
}