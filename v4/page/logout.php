<?php
session_start();
session_unset();
session_destroy();
header('Location: /v4/page/login.php');
exit;
