<?php

if(empty($_POST[$table]['balance_amount']))
{
    unset($_POST[$table]['balance_amount']);
}

if(empty($_POST[$table]['budget_amount']))
{
    unset($_POST[$table]['budget_amount']);
}