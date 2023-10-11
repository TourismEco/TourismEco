<?php

// Cette classe est un refactor pour accéder à la base de données
// pour que vous n'ayez pas à gérer l'accès à la base de données
class Mysql {
    private static $instance = null;
    private $connection;

    private function __construct($host, $username, $password, $database) {
        $this->connection = new mysqli($host, $username, $password, $database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getDB($host="localhost", $username="root", $password="root", $database="projet") {
        if (this::$instance === null) {
            this::$instance = new Mysql($host, $username, $password, $database);
        }
        return self::$instance;
    }

    public function query(string $query) {
        return $this->connection->query($query);
    }

    public function prepare(string $query) {
        return $this->connection->prepare($query);
    }

    public function execute(string $query) {
        return $this->connection->execute($query);
    }

    public function close() {
        $this->connection->close();
    }
}
