<?php

// Connect to the database
function getDB($hostname="localhost", $username="root", $password="", $database="ecotourisme") {
    try {
        $conn =  new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_TIMEOUT, 1800);
        return $conn;
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
}

