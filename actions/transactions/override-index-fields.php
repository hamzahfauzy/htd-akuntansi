<?php

unset($fields['subject_id']);

$fields = array_merge([
    'subject_name' => [
        'label' => 'Subjek',
        'type'  => 'text',
        'search' => false
    ]
], $fields);

return $fields;