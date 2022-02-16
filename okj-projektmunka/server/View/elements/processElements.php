<?php
require_once "navbars.php";
require_once "modals.php";
require_once "cardTemplates.php";
require_once "practiceTemplates.php";
require_once "statistics.php";
require_once "profile.php";
function getElement($request, $conn)
{
    switch ($request[1]) {
        case "primaryNavbar":
            return json_encode(["element" => createPrimaryNavbar($request[2])]);
            break;
        case "secondaryNavbar":
            return json_encode(["element" => createSecondaryNavbar($conn)]);
            break;
        case "practiceNavbar":
            return json_encode(["element" => createPracticeNavbar($_SESSION['sets']->getSetById($_SESSION['selectedSet']))]);
            break;
        case "cardsNavbar":
            return json_encode(["element" => createCardsNav($_SESSION['sets']->getSetById($_SESSION['selectedSet']))]);
            break;
        case "registerModal":
            return json_encode(["element" => registerModal()]);
            break;
        case "msgModal":
            return json_encode(["element" => msgModal($request[2])]);
            break;
        case "confirmModal":
            return json_encode(["element" => confirmModal($request[2], $request[3])]);
            break;
        case "createSetModal":
            return json_encode(["element" => createSetModal($conn)]);
            //     break;
            // case "getSet":
            //     return json_encode(["state" => "success", "elements" => createSets($request[2])]);
            //     break;
        case "getSets":
            if (isset($_SESSION['sets']) && !empty($_SESSION['sets'])) {
                $sets = [];
                foreach ($_SESSION['sets']->getSets() as $set) {
                    array_push($sets, createSets($set));
                }
                return json_encode(["state" => "success", "elements" => $sets]);
            } else {
                throw new Exception('Jelenleg nincsenek leckéi!');
            }
            break;
        case "modifySet":
            return json_encode(["state" => "success", "element" => modifyModal((array)$request[2])]);
            break;
        case "getCards":
            if (isset($_SESSION['cards']) && (count($_SESSION['cards']->getCards()) > 0)) {
                $cards = [];
                foreach ($_SESSION['cards']->getCards() as $card) {
                    array_push($cards, createCards($card));
                }
                return json_encode(["state" => "success", "elements" => $cards]);
            } else {
                return json_encode(['state' => 'error', 'msg' => 'A lecke jelenleg nem tartalmaz kártyákat!']);
            }
            break;
        case "practiceSet":
            return json_encode(["state" => "success", 'element' => practiceMatch()]);
            break;
        case 'createStatistics':
            return json_encode(['state' => 'success', 'statistics' => createStatistics($request[2], $request[3])]);
            break;
        case 'getProfileData':
            $name = $_SESSION['user']->getUserData()[1];
            $email = $_SESSION['user']->getUserData()[2];
            return json_encode(['state' => 'success', 'element' => createProfile($_SESSION['user']->getUserData()), 'userData' => [$name, $email]]);
            break;
    }
}
