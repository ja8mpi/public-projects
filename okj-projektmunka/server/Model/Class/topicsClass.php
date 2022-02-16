<?php

class Topics
{
    private $topics = array();
    function __construct($conn)
    {
        $stmt = $conn->prepare("SELECT * FROM topics");
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->topics, $result);
        }
    }
    function getTopics()
    {
        return $this->topics;
    }
}
