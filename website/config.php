<?php

// Defintion of constants
define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "ecotourisme");
define("SOURCE", "/projet_L3/website/");
define("SITE_URL", "http://localhost/projet_L3/website/");
define("MAPS_API_KEY", "AIzaSyBxhDyE21v8EJO7dxEet9uEB0KHsV5bLg4");

// Handling PHP errors
// TODO: Set to 0 in production
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Function to handle exceptions and log them
function exception_handler($exception) {
    $message =  "Error: [" . $exception->getCode() . "] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
    error_log($message, 3, SOURCE."logs/errors.log");
  }
set_exception_handler("exception_handler");


// Connect to the database
function getDB($hostname=DB_HOSTNAME, $username=DB_USERNAME, $password=DB_PASSWORD, $database=DB_DATABASE) {
    try {
        $conn =  new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_TIMEOUT, 1800);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
}

// Defining function to allow the use of variables in heredoc
$_ = function ($val){return $val;};

// Start session
session_start();