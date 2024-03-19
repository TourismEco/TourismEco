<?php

require_once "private.php";

// Defintion of constants
define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "ecotourisme");
define("SOURCE", "/projet_L3/website/");
define("SITE_URL", "http://localhost/projet_L3/website/");

// Handling PHP errors
// TODO: Set to 0 in production
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Function to handle exceptions and log them
function exception_handler($exception) {
    $message =  "Error: [" . $exception->getCode() . "] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
    error_log($message, 3, SOURCE."logs/errors.log");
  }
set_exception_handler("exception_handler");

// Defining function to allow the use of variables in heredoc
$_ = function ($val){return $val;};
// Start session
session_start();