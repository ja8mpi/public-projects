<?php
class Cards
{
    private $cards = [];
    function __construct($conn, $setId)
    {
        $stmt = $conn->prepare("SELECT * from cards WHERE setId =:setId");
        $stmt->bindParam(":setId", $setId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($this->cards, $result);
            }
            return true;
        } else {
            return false;
            // throw new Exception("A gyűjtemény jelenleg nem tartalmaz kártyákat");
        }
    }
    function getCards()
    {
        return $this->cards;
    }
    static function addCards($conn, $card, $setId)
    {

        $stmt = $conn->prepare("INSERT INTO cards(setId,statement, definition ,box)
            VALUES(:setId,:statement, :definition, :box)");
        $stmt->bindParam(':setId', $setId);
        $stmt->bindParam(':statement', $card['statement']);
        $stmt->bindParam(':definition', $card['definition']);
        $stmt->bindParam(':box', $card['box']);
        return ($stmt->execute());
    }
    static function deleteCard($conn, $cardId)
    {
        $stmt = $conn->prepare("DELETE FROM cards WHERE id =:cardId");
        $stmt->bindParam(':cardId', $cardId);
        if ($stmt->execute()) {
            return true;
        }
    }
    static function updateCards($conn, $card)
    {
        $sql = "UPDATE cards SET statement=:statement, definition=:definition, box=:box WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':statement', $card['statement']);
        $stmt->bindParam(':definition', $card['definition']);
        $stmt->bindParam(':box', $card['box']);
        $stmt->bindParam(':id', $card['id']);
        // $stmt->bindValue(':setId', $card['setId']);
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Nem sikerült a mentés!');
        }
    }
}

// function __construct($conn, $userId)
//     {
//         $stmt = $conn->prepare("SELECT * FROM sets WHERE userId=:userId ");
//         if (is_numeric($userId)) {
//             $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
//             $stmt->execute();
//             if ($stmt->rowCount() < 1) {
//                 throw new Exception("Jelenleg nincsenek gyűjteményei");
//             } else {
//                 $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
//                 while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
//                     array_push($this->sets, $result);
//                 }
//             }
//         } else {
//             throw new Exception("Hibás bemeneti paraméterek");
//         }
//     }