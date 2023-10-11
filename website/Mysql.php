<?php

// Cette classe est un refactor pour accéder à la base de données
// pour que vous n'ayez pas à gérer l'accès à la base de données
class Mysql {
    private static $instance = null;
    private $connection;

    private function __construct($hostname, $username, $password, $database) {
        try {
            $this->connection = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
          } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
          }
    }

    public static function getDB($host="localhost", $username="root", $password="root", $database="projet") {
        if (self::$instance === null) {
            self::$instance = new Mysql($host, $username, $password, $database);
        }
        return self::$instance;
    }

    public function query(string $query) {
        return $this->connection->query($query);
    }

    public function prepare(string $query) {
        return $this->connection->prepare($query);
    }

    public function close():bool {
        if($this->connection instanceof mysqli){
            return $this->connection->close();
        }
        else{
            return true; //there is no close function for PDO
        }
    }
}
