<?php

Page::set_title("Panel Jurnal");
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

return compact('success_msg');