<?php
/**
 * Program: config.php
 *
 * Shared configuration and bootstrap for the BetterPay site.
 */

session_start();

date_default_timezone_set('Africa/Johannesburg');
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/functions.php';
