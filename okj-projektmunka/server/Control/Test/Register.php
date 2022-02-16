<?php
function Registration($conn, $type, $step, $name, $email, $pwd)
{
    switch ($step) {
        case "name":
            try {
                return json_encode(["state" => "success", "msg" => validateName($conn, $type, $name)]);
            } catch (Exception $ex) {
                return json_encode(["state" => "error", "msg" => $ex->getMessage()]);
            }
            break;
        case "email":
            try {
                return json_encode(["state" => "success", "msg" => validateEmail($conn, $type, $email)]);
            } catch (Exception $ex) {
                return json_encode(["state" => "error", "msg" => $ex->getMessage()]);
            }
            break;
        case "final":
            try {
                return json_encode(["state" => "success", "msg" => validateRegistration($conn, $type, $name, $email, $pwd)]);
            } catch (Exception $ex) {
                return json_encode(["state" => "error", "msg" => $ex->getMessage()]);
            }
            break;
    }
}
