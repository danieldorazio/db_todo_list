<?php

// collegare al mio sql
define("DB_SERVER", "localhost:3306");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "todo_list_db");

// stabilire una connessione 
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// connessione non riuscita
if ($connection && $connection->connect_error) {
    echo "Connection failed";
    echo $connection->connect_error;
    die;
}

// recupero dati dal form
if (!empty($_POST['inputUsername']) && !empty($_POST['inputPassword'])) {
    $username = $_POST['inputUsername'];
    $password = $_POST['inputPassword'];
    $hashed_passwd = md5($password);

    $sql = "INSERT INTO `users` (`ID`, `username`, `password`) VALUES (NULL, '$username', '$hashed_passwd')";
    if ($connection->query($sql) === FALSE) {
        echo "Errore nell'inserimento dei dati: " . $connection->error;
    }

    header("Location: index.php?subscribe=success");
} else {
    if (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])) {
        header("Location: subscribe.php?subscribe=failed");
    }
}

$connection->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-5">

        <div class="row">

            <div class="col bg-light p-0">
                <h2 class="text-left mb-5">Subscribe</h2>

                <form action="subscribe.php" method="POST">

                    <div class="mb-3 d-flex align-items-center  justify-content-center w-50 mx-auto  bg-white ">
                        <label for="inputUsername" class="form-label p-2">username</label>
                        <input type="text" class="form-control border-0  bg-white " id="inputUsername" name="inputUsername">
                    </div>

                    <div class="mb-3 d-flex align-items-center  justify-content-center w-50 mx-auto bg-white ">
                        <label for="inputPassword" class="form-label p-2">Password</label>
                        <input type="password" class="form-control border-0  bg-white " id="inputPassword" name="inputPassword">
                    </div>

                    <div class="d-flex align-items-center w-50 mx-auto gap-2">
                        <button type="submit" class="btn btn-primary w-50 ">Sign in</button>
                    </div>
                </form>
            </div>

            <div class="col d-flex justify-content-start p-0"><img class="w-75" src="./img/twitterlists-TA.webp" alt=""></div>
        </div>
        <?php if(isset($_GET['subscribe']) && $_GET['subscribe'] == 'failed') { ?>
        <div> CAMPI VUOTI</div>
        <?php }?>
    </div>
</body>

</html>