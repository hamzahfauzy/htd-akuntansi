<?php
$params = [];
if(isset($_GET['parent_id']) && !empty($_GET['parent_id']))
{
    $params['parent_id'] = $_GET['parent_id'];
}
set_flash_msg(['success'=>_ucwords(__($table)).' berhasil ditambahkan']);
header('location:'.routeTo('accounts/index', $params));
die();