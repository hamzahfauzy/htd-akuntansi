<?php

$group = $db->single('subject_groups',['user_id'=>$data->user_id]);
$data->group = $group->group_id;

return $data;