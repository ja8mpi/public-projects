<?php
function LoginValidation($conn, $type, $name, $pwd)
{
    try {
        return ["state" => "success", "msg" => validateLogin($conn, $type, $name, $pwd)];
    } catch (Exception $ex) {
        return ["state" => "error", "msg" => $ex->getMessage()];
    }
}
