<?php

// require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'userClass.php';
// require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'connectionClass.php';

// session_start();
class Sets
{
    // private $topics = array(); 
    private $sets = array();
    function __construct($conn, $userId)
    {
        $stmt = $conn->prepare("SELECT 
                sets.id,
                sets.title,
                sets.userId,
                sets.topicId,
                topics.topic 
            FROM sets 
            INNER JOIN topics on topics.id = sets.topicId 
            WHERE userId=:userId ");
        if (is_numeric($userId)) {
            $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() < 1) {
                throw new Exception("Jelenleg nincsenek gyűjteményei");
            } else {
                // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($this->sets, $result);
                }
            }
        }
    }
    static function createSet($conn, $userId, $title, $topicId)
    {

        $stmt = $conn->prepare("INSERT INTO SETS(title,userId,topicId)
        VALUES(:title, :userId, :topicId)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':topicId', $topicId);
        $stmt->execute();
        if ($stmt) {
            $stmt = $conn->prepare("SELECT * FROM sets 
            WHERE userId=:userId AND sets.title =:title");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $result;
            }
        } else {
            throw new Exception("Nem sikerült létrehozni a leckét!");
        }
    }
    function getSetById($id)
    {
        $returnset = [];
        foreach ($this->sets as $set) {
            if ($set['id'] == $id) {
                $returnset = $set;
            }
        }
        return $returnset;
    }
    function getSetByTitle($title)
    {
        $exist = false;
        foreach ($this->sets as $set) {
            if ($set['title'] === $title) {
                $exist = true;
            }
        }
        return $exist;
    }
    function getSetByTitleDatabase($conn, $title, $userId)
    {
        $returnset = [];
        $stmt = $conn->prepare("SELECT * FROM sets WHERE sets.title =:title AND userId=:userId");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $result;
        }
        foreach ($this->sets as $set) {
            if ($set['title'] == $title) {
                $returnset = $set;
            }
        }
        return $returnset;
    }
    static function deleteSet($conn, $setId)
    {
        $setId = (int)$setId;
        $stmt = $conn->prepare("DELETE FROM sets WHERE id =:setId");
        $stmt->bindParam(':setId', $setId);
        if ($stmt->execute()) {
            // for ($i = 0; $i < count(self::$sets); $i++) {
            //     if (self::$sets[$i]['id'] === $setId) {
            //         unset(self::$sets[$i]);
            //     }
            // }
        } else {
            throw new Exception('Nem sikerült a lecke törlése!');
        }
    }
    static function haveCards($conn, $setId)
    {
        $setId = (int)$setId;
        $stmt = $conn->prepare("SELECT * FROM cards WHERE setId=:setId LIMIT 1");
        $stmt->bindParam(':setId', $setId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    static function deleteCards($conn, $setId)
    {
        $stmt = $conn->prepare("DELETE FROM cards WHERE setId=:setId");
        $stmt->bindParam(':setId', $setId);
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Nem sikerült a lecke törlése!');
        }
    }
    static function updateSet($conn, $setToUpdate)
    {
        $set = (array)$setToUpdate;
        // $id = (int)$set['id'];
        // $topicId = (int)$set['topic'];
        $sql = "UPDATE sets SET title=:title, topicId=:topicId WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $set['title']);
        $stmt->bindParam(':topicId', $set['topicId'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $set['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
    function addSet($newset)
    {
        array_push($this->sets, $newset);
    }
    function getSets()
    {
        return $this->sets;
    }
    function removeSetById($setId)
    {
        foreach ($this->sets as $set) {
            if ($set['id'] == $setId) {
                unset($this->sets, $set);
            }
        }
    }
    function search_topic($search_topic, $set_topic)
    {
        if ($search_topic == $set_topic) {
            return true;
        } else {
            return false;
        }
    }
    //Keresendő lecke címének és létező leckék címeinek összevetése
    function search_title($search_title, $set_title)
    {
        return preg_match($search_title, $set_title);
    }
    //Kerső függvény
    function searcher($request)
    {
        $tmp = array();
        if ((isset($request[1]) && !empty($request[1])) && (isset($request[2]) && !empty($request[2]))) {
            //echo ("Van beállítva keresendő szöveg és szűrő is!");
            foreach ($this->sets as $set) {
                if ($this->search_topic($request[1], $set['topicId']) && $this->search_title('/' . $request[2] . '/i', $set['title'])) {
                    array_push($tmp, $set);
                }
            }
        } else if ((isset($request[2]) && !empty($request[2])) && (empty($request[1]))) {
            //echo ("Van beállítva keresendő szöveg de szűrő nem!");
            foreach ($this->sets as $set) {
                if ($this->search_title('/' . $request[2] . '/i', $set['title'])) {
                    array_push($tmp, $set);
                }
            }
        } else if ((empty($request[2])) && (isset($request[1]) && !empty($request[1]))) {
            //echo ("Nincs beállítva keresendő szöveg de szűrő igen!");
            foreach ($this->sets as $set) {
                if ($this->search_topic($request[1], $set['topicId'])) {
                    array_push($tmp, $set);
                }
            }
        }
        $this->sets = $tmp;
    }
}
// $conn = new Connection("mysql:host=localhost;dbname=projektmunka_technikum", "root", "");

// try {
//     $conn->connect();
// } catch (Exception $ex) {
//     echo ($ex->getMessage());
// }

// try {
//     $sets = new Sets($conn->getconn(), 24);
// } catch (Exception $ex) {
//     echo $ex->getMessage();
// }

// $_SESSION['sets']->deleteSet($conn->getConn(), 41);
// foreach ($_SESSION['sets'] as $set) {
//     if ($set['id'] == 41) {
//         unset($_SESSION['sets'], $set);
//     }
// }
// $conn = new Connection("mysql:host=localhost;dbname=projektmunka_technikum", "root", "");
// $conn->connect();
// // $user = $_SESSION['user']->getUserData();
// try {
//     $sets =  new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
//     foreach ($sets->getSets() as $set) {
//         foreach ($set as $data) {
//             echo $data . " ";
//         }
//         echo "<br>";
//     }
// } catch (Exception $ex) {
//     echo $ex->getMessage();
// }
