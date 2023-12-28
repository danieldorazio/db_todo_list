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
} else {
    echo "campi vuoti";
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

        <h2 class="text-center">Subscribe</h2>

        <div class="card w-50 mx-auto">
            <div class="card-body">

                <form class="row g-3" action="subscribe.php" method="POST">
                    <div class="col-md-6">
                        <label for="inputUsername" class="form-label">username</label>
                        <input type="text" class="form-control" id="inputUsername" name="inputUsername">
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>