<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'connectionClass.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'userClass.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'setsClass.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'cardsClass.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Class' . DIRECTORY_SEPARATOR . 'topicsClass.php';
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'processElements.php';
require_once "Test/inputValidation.php";
require_once "Test/Login.php";
require_once "Test/Register.php";

session_start();
// require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Control' . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'Login.php';
// require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Control' . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'Register.php';

$conn = new Connection("mysql:host=localhost;dbname=projektmunka_technikum", "root", "");

try {
    $conn->connect();
} catch (Exception $ex) {
    echo ($ex->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['request'])) {
        $request = json_decode($_POST['request']);
        try {
            switch ($request[0]) {
                case "getElement":
                    try {
                        echo getElement($request, $conn->getConn());
                    } catch (Exception $ex) {
                        echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                    }
                    break;
                case "register":
                    echo Registration($conn->getConn(), $request[0], $request[1], $request[2], $request[3], $request[4]);
                    break;
                case "login":
                    $loginStatus = LoginValidation($conn->getConn(), $request[0], $request[1], $request[2]);
                    if ($loginStatus['state'] === "success") {
                        $user = new User($conn->getConn(), $request[1]);
                        $_SESSION['user'] = $user;
                        $_SESSION['login_status'] = true;
                        $_SESSION['location'] = "homepage";
                        echo json_encode($loginStatus);
                    } else {
                        echo json_encode($loginStatus);
                    }
                    break;
                case "updateUserName":
                    if (!empty($request[1])) {
                        try {
                            $_SESSION['user']->updateName($conn->getConn(), $request[1]);
                            echo json_encode(['state' => 'success']);
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } else {
                        echo json_encode(['state' => 'error', 'msg' => 'Kérem töltse ki a beviteli mezőt!']);
                    }
                case "updateEmail":
                    if (!empty($request[1])) {
                        try {
                            $_SESSION['user']->updateEmail($conn->getConn(), $request[1]);
                            echo json_encode(['state' => 'success']);
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } else {
                        echo json_encode(['state' => 'error', 'msg' => 'Kérem töltse ki a beviteli mezőt!']);
                    }
                    break;
                case "updatePassword":
                    if (!empty($request[1])) {
                        try {
                            $_SESSION['user']->updatePassword($conn->getConn(), $request[1]);
                            echo json_encode(['state' => 'success']);
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } else {
                        echo json_encode(['state' => 'error', 'msg' => 'Kérem töltse ki a beviteli mezőt!']);
                    }
                    break;
                case "logout":
                    session_unset();
                    session_destroy();
                    echo json_encode(["state" => "success"]);
                    break;
                case "location":
                    $_SESSION['location'] = $request[1];
                    if ($request[1] == "sets") {
                        try {
                            $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                            echo json_encode(['state' => 'success']);
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'success', 'msg' => $ex->getMessage(), 'file' => 'sets.js']);
                        }
                        $_SESSION['topics'] = new Topics($conn->getConn());
                    } else if ($request[1] == "cards") {
                        unset($_SESSION['cards']);
                        try {
                            if (isset($request[2]) && !empty($request[2])) {
                                $_SESSION['selectedSet'] = $request[2];
                            }
                            if ($_SESSION['cards'] = new Cards($conn->getConn(), $_SESSION['selectedSet'])) {
                                echo json_encode(["state" => "success"]);
                            } else {
                                echo json_encode(["state" => "error", 'msg' => 'A lecke jelenleg nem tartalmaz kártyákat.']);
                            }
                        } catch (Exception $ex) {
                            echo json_encode(["state" => "error", 'msg' => $ex->getMessage()]);
                        }
                    } else {
                        echo json_encode(['state' => 'success']);
                    }
                    break;
                case "open_set":
                    break;
                case "check_test":
                    try {
                        if (isset($_SESSION['sets']) && !empty($_SESSION['sets'])) {
                            if ($_SESSION['sets']->getSetByTitle($request[2])) {
                                echo json_encode(['state' => "error", "msg" => "Már van ilyen leckéje!"]);
                            } else {
                                echo json_encode(['state' => "success"]);
                            }
                        } else {
                            echo json_encode(['state' => "success"]);
                        }
                    } catch (\Error $e) {
                        echo json_encode(['state' => "error", "msg" => $e->getMessage()]);
                    }
                    // if (count((array)$_SESSION['sets']->getSetByTitle($conn->getConn(), $request[2], $_SESSION['user']->getUserData()[0])) > 0) {
                    //     // http_response_code(500);
                    //     echo json_encode(['state' => "error", "msg" => "Már van ilyen leckéje!"]);
                    // } else {
                    //     echo json_encode(['state' => "success"]);
                    // }
                    // if (isset($_SESSION['sets'])) {
                    //     try {
                    //         if (count((array)$_SESSION['sets']->getSetByTitle($conn->getConn(), $request[2], $_SESSION['user']->getUserData()[0])) > 0) {
                    //             http_response_code(500);
                    //             echo json_encode(['state' => "error", "msg" => "Már van ilyen leckéje!"]);
                    //         }
                    //     } catch (\Error $e) {
                    //         http_response_code(500);
                    //         echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
                    //     }
                    // } else {
                    //     echo json_encode(['state' => "success"]);
                    // }
                    break;
                case "create_set":
                    $title = testInput($request[1]);
                    $topicId = testInput($request[2]);
                    try {
                        try {
                            Sets::createSet($conn->getConn(), $_SESSION['user']->getUserData()[0], $title, $topicId);
                            if (!isset($_SESSION['sets'])) {
                                $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                            } else {
                                unset($_SESSION['sets']);
                                $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                            }
                            echo (json_encode(['state' => 'success', 'sets' => $_SESSION['sets']->getSets()]));
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } catch (\Error $e) {
                        echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
                    }
                    break;
                case "getSets":
                    if (isset($_SESSION['sets']) && !empty($_SESSION['sets'])) {
                        echo json_encode(['state' => 'success', 'sets' => $_SESSION['sets']->getSets()]);
                    } else {
                        echo json_encode(['state' => 'error', 'msg' => 'Jelenleg nincsenek leckéi']);
                    }
                    break;
                case "deleteSet":
                    try {
                        if (Sets::haveCards($conn->getConn(), (int)$request[1])) {
                            Sets::deleteCards($conn->getConn(), (int)$request[1]);
                        }
                        Sets::deleteSet($conn->getConn(), (int)$request[1]);
                        // $_SESSION['sets']->removeSetById($request[1]);
                        unset($_SESSION['sets']);
                        $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                        echo json_encode(['state' => 'success']);
                    } catch (Exception $ex) {
                        echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                    }
                    break;
                case "update_set":
                    // echo json_encode(['state' => 'success', 'sets' => gettype(((array)$request[1])['id'])]);
                    try {
                        try {
                            Sets::updateSet($conn->getConn(), $request[1]);
                            unset($_SESSION['sets']);
                            $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);

                            echo json_encode(['state' => 'success', 'sets' => $_SESSION['sets']->getSets()]);
                        } catch (Exception $ex) {
                            http_response_code(500);
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } catch (\Error $e) {
                        http_response_code(500);
                        echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
                    }
                    break;
                case "search_sets":
                    unset($_SESSION['sets']);
                    $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                    if (!empty($request[1]) || !(empty($request[2]))) {
                        try {
                            $_SESSION['sets']->searcher($request);
                            echo json_encode(['state' => 'success']);
                        } catch (\Error $e) {
                            http_response_code(500);
                            echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
                        }
                        break;
                    } else {
                        unset($_SESSION['sets']);
                        $_SESSION['sets'] = new Sets($conn->getConn(), $_SESSION['user']->getUserData()[0]);
                    }
                case "practice_set":
                    // echo json_encode(['cards' => $_SESSION['cards']->getCards()]);
                    break;
                case "get_cards":
                    try {
                        if (isset($_SESSION['cards']) && (count($_SESSION['cards']->getCards()) > 0)) {
                            echo json_encode(["state" => "success", "cards" => $_SESSION['cards']->getCards()]);
                        } else {
                            echo json_encode(["state" => "error", "msg" => 'A lecke jelenleg nem tartalmaz kártyákat']);
                        }
                    } catch (\Error $e) {
                        echo json_encode(["state" => "error", "msg" => $e->getMessage()]);
                    }
                    break;
                case "add_card":
                    echo json_encode(["element" => createRawCard()]);
                    break;
                case "update_cards":
                    $cardsState = false;
                    try {
                        //Új kártyák hozzáadása 
                        if (isset($request[2]) && !empty($request[2])) {
                            foreach ($request[2] as $card) {
                                if (Cards::addCards($conn->getConn(), (array) $card, $_SESSION['selectedSet'])) {
                                    $cardsState = true;
                                } else {
                                    $cardsState = false;
                                }
                            }
                        }
                        //Régi kártyák módosítása
                        if (isset($request[1]) && !empty($request[1])) {
                            for ($i = 0; $i < count($request[1]); $i++) {
                                $cardsState = Cards::updateCards($conn->getConn(), (array)$request[1][$i]);
                            }
                        }
                        if ($cardsState) {
                            if (isset($_SESSION['cards']) && (count($_SESSION['cards']->getCards()) > 0)) {
                                unset($_SESSION['cards']);
                            }
                            $_SESSION['cards'] = new Cards($conn->getConn(), $_SESSION['selectedSet']);
                        }
                        echo json_encode(['state' => 'success', 'msg' => 'Sikeres mentés!']);
                    } catch (Exception $ex) {
                        echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                    }

                    // echo json_encode(['cards' => $request[1], 'newCards' => $request[2]]);
                    break;
                case "delete_card":
                    try {
                        try {
                            if (Cards::deleteCard($conn->getConn(), $request[1])) {
                                unset($_SESSION['cards']);
                                $_SESSION['cards'] = new Cards($conn->getConn(), $_SESSION['selectedSet']);
                                if (count($_SESSION['cards']->getCards()) > 0) {
                                    echo json_encode(['state' => 'success', 'cards' => $_SESSION['cards']->getCards()]);
                                } else {
                                    echo json_encode(['state' => 'error', 'msg' => 'A lecke jelenleg nem tartalmaz kártyákat']);
                                }
                            }
                        } catch (Exception $ex) {
                            echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                        }
                    } catch (\Error $e) {
                        echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
                    }
                    break;
                case "save_cards":
                    $returncards = [];
                    $state = false;
                    try {
                        for ($i = 0; $i < count($request[1]); $i++) {
                            $state = Cards::updateCards($conn->getConn(), (array)$request[1][$i]);
                        }
                        if ($state) {
                            unset($_SESSION['cards']);
                            $_SESSION['cards'] = new Cards($conn->getConn(), $_SESSION['selectedSet']);
                            echo json_encode(['state' => 'success', 'msg' => 'Sikeres mentés!']);
                        } else {
                            echo json_encode(['state' => 'error', 'msg' => 'Nem sikerült a mentés!']);
                        }
                    } catch (Exception $ex) {
                        echo json_encode(['state' => 'error', 'msg' => $ex->getMessage()]);
                    }
                    break;
            }
        } catch (\Error $e) {
            echo json_encode(['state' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    // $request = json_decode($_POST['request']);
    // try {
    //    testuser User::validateName($conn->getConn(), testInput($request[1]));
    // } catch (Exception $ex) {
    //     echo $ex->getMessage();
    // }
}
