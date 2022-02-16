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
    public function getSingleData($table, $element)
    {
        return  $this->conn;
    }
}
class User extends Connection
{
    protected $id;
    protected $username;
    protected $email;
    protected $password;
    static function checkName($conn, $name)
    {
        $stmt = $conn->prepare("SELECT username FROM users WHERE username=:name ");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    static function checkEmail($conn, $email)
    {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email=:email ");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        if (count($stmt->fetchAll()) > 0) {
            return true;
        } else {
            return false;
        }
    }
    static function checkPassword($conn, $name)
    {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username=:name");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }
    static function createUser($conn, $name, $email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users(username, email, password)
        VALUES(:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
    function __construct($conn, $name)
    {
        $stmt = $conn->prepare("SELECT id,username, email, password FROM users WHERE username=:name");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = $data['id'];
            $this->username = $data['username'];
            $this->email = $data['email'];
            $this->password = $data['password'];
        }
    }
    function getUserData()
    {
        $returnarray = [$this->id, $this->username, $this->email, $this->password];
        return $returnarray;
    }
}

$conn = new Connection("mysql:host=localhost;dbname=projektmunka_technikum", "root", "");
$password = "Admin#123";
$name = "bollokakos";
$conn->connect();

$user = new User($conn->getConn(), "bollokakos");
$userdata = $user->getUserData();

foreach ($userdata as $data) {
    echo $data . " ";
}


// $stmt = $conn->getConn()->prepare("SELECT password FROM users WHERE username=:name");
// $stmt->bindParam(":name", $name, PDO::PARAM_STR);
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// echo password_verify($password, $result['password']);
