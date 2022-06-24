<?php
session_start();

function isUserLogin()
{
    if (isset($_SESSION['user'])) {
        return true;
    }

    return false;
}
