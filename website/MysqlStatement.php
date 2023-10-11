<?php

class MysqlStatement
{
    private $stmt;
    private $query;

    public function __construct(mysqli_stmt|PDOStatement $stmt) {
        $this->stmt = $stmt;
    }


    public function execute($params = null) {
        if($this->stmt instanceof PDOStatement) {
            if ($params) {
                return $this->stmt->execute($params);
            } else {
                return $this->stmt->execute();
            }
        } else {
            //$this->stmt->execute_query();
            return null; //pour le moment le temps que je debug...
        }
    }

    public function fetch() {
        return $this->stmt->fetch();
    }

    public function close() {
        if($this->stmt instanceof PDOStatement) {
            return $this->stmt->closeCursor();
        } else {
            return $this->stmt->close();
        }
}
    

    public function getStmt() {
        return $this->stmt;
    }
}
