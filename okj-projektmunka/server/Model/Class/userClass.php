<?php
class User
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
    function updateUser($conn, $data)
    {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users(UPDATE users SET name=:name, email=:email,password=:password WHERE id=:id)
        VALUES(:name, :email, :password)");
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $this->username = $data['name'];
        $this->email = $data['email'];
        $this->password = $password;
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
    function updateName($conn, $name)
    {
        // $stmt = $conn->prepare("SELECT username FROM users WHERE username=:name");
        // $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        // $stmt->execute();
        // if (count($stmt->fetchAll()) > 0) {
        //     return true;
        // } else {
        $stmt = $conn->prepare("UPDATE users SET username=:username WHERE id=:id");
        $stmt->bindParam(":username", $name, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $this->username = $name;
        // }
    }
    function updateEmail($conn, $email)
    {
        // $stmt = $conn->prepare("SELECT username FROM users WHERE email=:email");
        // $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        // $stmt->execute();
        // if (count($stmt->fetchAll()) > 0) {
        //     return true;
        // } else {
        $stmt = $conn->prepare("UPDATE users SET email=:email WHERE id=:id");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $this->email = $email;
        // }
    }
    function updatePassword($conn, $pwd)
    {
        $password = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=:password WHERE id=:id");
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $this->password = $password;
    }
}



//ADMIN FELÜLETHEZ:

// $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username ");
//         if (is_string($username)) {
//             $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//             $stmt->execute();
//             if ($stmt->rowCount() < 1) {
//                 throw new Exception("Nincs a keresésnek mefelelő felhsználónév");
//             } else {
//                 $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
//                 $result = [];
//                 while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
//                     $this->id = $result['id'];
//                     $this->username = $result['username'];
//                     $this->email = $result['email'];
//                     $this->password = $result['password'];                //Ha itt array push-olunk, akkor egy nested array a vissaztérés, így viszont egy associative array
//                 }
//             }
//         } else {
//             throw new Exception("Hibás bemeneti paraméterek");
//         }