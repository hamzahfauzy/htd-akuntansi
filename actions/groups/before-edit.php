<?php

if(empty($_POST[$table]['parent_id']) || $_POST[$table]['parent_id'] == '')
{
    unset($_POST[$table]['parent_id']);
}