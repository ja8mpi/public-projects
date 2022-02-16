<?php
class Connection
{
    protected $dsn;
    protected $username;
    protected $pwd;
    protected $conn;
    function __construct($dsn, $username, $pwd)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->pwd = $pwd;
    }
    public function connect()
    {
        if ($this->conn = new PDO($this->dsn, $this->username, $this->pwd)) {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            throw new Exception("Can't connect to this motherfucker");
        }
    }
    public function getConn()
    {
        return $this->conn;
    }
}
