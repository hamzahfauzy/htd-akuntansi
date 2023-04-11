<?php

// validation
if(empty($_POST[$table]['debt_bill_account_id']))
{
    unset($_POST[$table]['debt_bill_account_id']);
}


if(empty($_POST[$table]['credit_bill_account_id']))
{
    unset($_POST[$table]['credit_bill_account_id']);
}