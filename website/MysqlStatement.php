<?php

class MysqlStatement
{
    private $stmt;

    public function __construct(mysqli_stmt $stmt) {
        $this->stmt = $stmt;
    }

    public function bindParam($param, &$var, $type, $maxlen = null, $driverdata = null) {
        return $this->stmt->bind_param($param, $var, $type, $maxlen, $driverdata);
    }

    public function execute($params = null) {
        if ($params) {
            return $this->stmt->execute($params);
        } else {
            return $this->stmt->execute();
        }
    }

    public function fetch() {
        return $this->stmt->fetch();
    }

    public function close() {
        return $this->stmt->close();
    }
    

    public function getStmt() {
        return $this->stmt;
    }
}
