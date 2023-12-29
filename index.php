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


//query di visualizzazione contenuto per privato per ogni user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT `id`, `nome_todo`, `status`, `user_id` FROM `todo_list` WHERE `user_id` = '$user_id'";
    $results = $connection->query($sql);
}

//query di inserimento di un nuovo todo
if (!empty($_POST['inputNewTodo'])) {
    $new_todo = $_POST['inputNewTodo'];


    $query = "INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`) VALUES (NULL, '$new_todo', '0', '$user_id')";
    $connection->query($query);

    header("Location: index.php?newTodo=success");
}


//query per cancellare il todo
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete'];

    $query_delete = "DELETE FROM todo_list WHERE `todo_list`.`id` = '$delete_id'";
    $connection->query($query_delete);

    header("Location: index.php?deleteTodo=success");
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

        <!-- verifica se l'utente è loggato => se user_id e username siano compilati -->
        <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>

            <?php if ($results && $results->num_rows > 0) { ?>
                <?php include_once __DIR__ . "/partials/header.php" ?>

                <h2 class="text-center p-5">TODO LIST</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">nome_todo</th>
                            <th scope="col">status</th>
                            <th scope="col">user_id</th>
                            <th></th>
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

                                <td>
                                    <form action="index.php" method="POST">
                                        <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="delete">
                                        <button type="submit" class="btn btn-danger">DELETE</button>
                                    </form>
                                </td>
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

          

                <div class="row">
                    <div class="col d-flex justify-content-end p-0"><img class="w-75" src="./img/twitterlists-TA.webp" alt=""></div>
                    <div class="col bg-light p-0">
        
                            <h2 class="text-left mb-5">LOGIN</h2>
                        
                            <form action="index.php" method="POST">

                                <div class="mb-3 d-flex align-items-center  justify-content-center w-50 mx-auto  bg-white ">
                                    <label for="name" class="form-label p-2">Username</label>
                                    <input type="text" class="form-control border-0  bg-white " id="name" name="username">
                                </div>

                                <div class="mb-3 d-flex align-items-center  justify-content-center w-50 mx-auto bg-white ">
                                    <label for="password" class="form-label p-2">Password</label>
                                    <input type="password" class="form-control border-0  bg-white " id="password" name="password">
                                </div>
                      

                                <div class="d-flex align-items-center w-50 mx-auto gap-2">
                                    <button type="submit" class="btn btn-primary w-50 ">Invia</button>
                                    <div class="btn btn-danger w-50"><a href="./subscribe.php" class="text-decoration-none text-white">Registrati</a></div>
                                </div>

                            </form>
                      
                    </div>
                </div>







        <?php } ?>
    </div>
</body>

</html>