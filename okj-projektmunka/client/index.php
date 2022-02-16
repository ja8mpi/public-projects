<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanulgató</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="scripts/functions.js"></script>
    <?php

    if (isset($_SESSION['login_status']) && isset($_SESSION['location'])) {

        switch ($_SESSION['location']) {
            case "sets":
                echo '<script id="pageJs" src="scripts/sets.js"></script>';
                break;
            case "homepage":
                echo '<script id="pageJs" src="scripts/homepage.js"></script>';
                break;
            case "profile":
                echo '<script id="pageJs" src="scripts/profile.js"></script>';
                break;
            case "cards":
                echo '<script id="pageJs" src="scripts/cards.js"></script>';
                break;
            case "practice":
                echo '<script id="pageJs" src="scripts/practice.js"></script>';
                break;
        }
    } else {
        echo '<script id="pageJs" src="scripts/index.js"></script>';
    }
    ?>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container">
    </div>

</body>

</html>