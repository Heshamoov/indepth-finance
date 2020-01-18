<?php

require_once 'include/loginFunction.php';

if (isset($_POST['token']) && $_POST['token'] != '') {
    login();
}

else {
    header('Location: index.php');
}