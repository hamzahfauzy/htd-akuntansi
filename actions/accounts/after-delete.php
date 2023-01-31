<?php
$params = [];
if($delete->parent_id)
{
    $params['parent_id'] = $edit->parent_id;
}
set_flash_msg(['success'=>_ucwords(__($table)).' berhasil diedit']);
header('location:'.routeTo('accounts/index', $params));
die();