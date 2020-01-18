<?php

require_once 'include/loginFunction.php';

session_start();

if (isset($_POST['token']) && $_POST['token']  != '') {
    login();
}

else {
    header('Location: index.php');
}