<?php

require_once __DIR__ . "/login.php";

//creare una sessione per il login
if (!isset($_SESSION)) {
    session_start();
}


// collegare il mysql
define("DB_SERVER", "localhost:3306");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "todo_list_db");


// stabilere una connessione
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// connessione non riuscita
if ($connection && $connection->connect_error) {
    echo "Connection failed";
    echo $connection->connect_error;
    die;
}

// verificare se eseguire l'operazione di login 
if (isset($_POST['username']) && isset($_POST['password'])) {
    login($_POST['username'], $_POST['password'], $connection);
}


//query di visualizzazione contenuto 
$sql = "SELECT `id`, `nome_todo`, `status`, `user_id` FROM `todo_list`";
$results = $connection->query($sql);

//query di inserimento di un nuovo todo
if (!empty($_POST['inputNewTodo'])) {
    $new_todo = $_POST['inputNewTodo'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`) VALUES (NULL, '$new_todo', '0', '$user_id')";
    $connection->query($query);

    header("Location: index.php?newTodo=success");
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



        <?php include_once __DIR__ . "/partials/header.php" ?>

        <!-- verifica se l'utente è loggato => se user_id e username siano compilati -->
        <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>

            <?php if ($results && $results->num_rows > 0) { ?>

                <h2 class="text-center p-5">TODO LIST</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">nome_todo</th>
                            <th scope="col">status</th>
                            <th scope="col">user_id</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ciclo while per mostare tutta la tabella  -->
                        <?php while ($row = $results->fetch_assoc()) { ?>
                            <tr>
                                <th scope="row"><?php echo $row['id'] ?></th>
                                <td><?php echo $row['nome_todo'] ?></td>
                                <td><?php echo $row['status'] ?></td>
                                <td><?php echo $row['user_id'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h2 class="text-center p-5">NEW TODO</h2>

                <div class="card w-50 mx-auto">
                    <div class="card-body">

                        <form class="row g-3" action="index.php" method="POST">
                            <div class="col-md-12">
                                <label for="inputNewTodo" class="form-label">Nome todo</label>
                                <input type="text" class="form-control" id="inputNewTodo" name="inputNewTodo">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>



            <?php } ?>

        <?php } else { ?>
            <!-- SE L'UTENTE NON è LOGGATO VEDRA IL FORM DI LOGIN  -->
            <h2 class="text-center">LOGIN</h2>

            <div class="card w-50 mx-auto">
                <div class="card-body">
                    <form action="index.php" method="POST">

                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="name" name="username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary">Invia</buttn>
                    </form>
                </div>
                <a href="./subscribe.php" class="btn btn-danger">Registrati</a>
            </div>

        <?php } ?>
    </div>
</body>

</html>