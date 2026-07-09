<?php
require_once "../app/helpers/SessionHelper.php";

SessionHelper::start();

session_unset();
session_destroy();

header("Location: login.php");
exit;
?>