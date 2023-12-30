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
    $sql = "SELECT `id`, `nome_todo`, `status`, `user_id`, `data`, `important` FROM `todo_list` WHERE `user_id` = '$user_id'";
    $results = $connection->query($sql);
}

//query di riceca di un todo
if (!empty($_GET['inputSearch'])) {
    $inputSearch = $_GET['inputSearch'];
    $sql = "SELECT `nome_todo`,`id`, `data` FROM `todo_list` WHERE `user_id` = '$user_id'
    AND `nome_todo` = '$inputSearch'";
    $results = $connection->query($sql);
}

//query di visualizzazione contenuto per privato per ogni user_id al click del tasto all 
if (isset($_GET['all']) && $_GET['all'] == 1) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT `id`, `nome_todo`, `status`, `user_id`, `data`, `important` FROM `todo_list` WHERE `user_id` = '$user_id'";
    $results = $connection->query($sql);
}

//query di visualizzazione contenuto per privato per ogni user_id del giorno odierno
if (isset($_GET['toDay']) && $_GET['toDay'] == 1) {
    $user_id = $_SESSION['user_id'];
    $time_stamp = date('y-m-d');
    $sql = "SELECT `id`, `nome_todo`, `status`, `user_id`, `data`, `important` FROM `todo_list` WHERE `user_id` = '$user_id' AND `data` = '$time_stamp'";
    $results = $connection->query($sql);
}


//query di inserimento di un nuovo todo
if (!empty($_POST['inputNewTodo']) && !empty($_POST['inputData'])) {
    $new_todo = $_POST['inputNewTodo'];
    $new_data = $_POST['inputData'];
    $query = "INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`, `data`) VALUES (NULL, '$new_todo', '0', '$user_id', '$new_data')";
    $connection->query($query);

    header("Location: index.php?newTodo=success");
}


//query per cancellare il todo
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete'];

    $query_delete = "DELETE FROM `todo_list` WHERE `todo_list`.`id` = '$delete_id'";
    $connection->query($query_delete);

    header("Location: index.php?deleteTodo=success");
}


// query che mi cambia lo status
if (isset($_POST['status'])) {
    $status_id = $_POST['status'];

    $query_status = "UPDATE `todo_list` SET status = NOT status WHERE `id` = '$status_id'";
    $connection->query($query_status);

    header("Location: index.php");
}

// query che mi cambia lo important
if (isset($_POST['important'])) {
    $important_id = $_POST['important'];

    $query_important = "UPDATE `todo_list` SET important = NOT important WHERE `id` = '$important_id'";
    $connection->query($query_important);

    header("Location: index.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <div class="container mt-5">

        <!-- verifica se l'utente è loggato => se user_id e username siano compilati -->
        <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>
            <?php if ($results && $results->num_rows > 0) { ?>
                <div class="row">
                    <div class="col bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-center p-5">Ciao <?php echo $_SESSION['username']; ?></h4>

                            <!-- BOTTONE DI LOGOUT -->
                            <form action="logout.php" method="POST">
                                <input type="hidden" type="text" value="1" name="logout">
                                <button type="submit" class="btn btn-outline-secondary ">Logout</button>
                            </form>
                        </div>

                        <form class="d-flex border border-3 rounded-pill mb-5 " action="index.php" method="GET">
                            <label for="search"></label>
                            <input type="text" class="form-control border-0" id="inputSearch" name="inputSearch" placeholder="SEARCH TASK">
                            <button type="submit" class="btn btn-outline-secondary border-0 "><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action border-0 ">
                                <form action="index.php" method="GET">
                                    <input type="hidden" type="text" value="1" name="all">
                                    <button type="submit" class="btn border-0 "><i class="fa-solid fa-house btn btn-outline-secondary border-0 "></i> <span class="ms-3">All Tasks</span></button>
                                </form>
                            </li>
                            <li class="list-group-item list-group-item-action border-0">
                                <form action="index.php" method="GET">
                                    <input type="hidden" type="text" value="1" name="toDay">
                                    <button type="submit" class="btn border-0 "><i class="fa-regular fa-sun btn btn-outline-secondary border-0 "></i> <span class="ms-3">Today's Tasks</span></button>
                                </form>
                            </li>
                        </ul>

                    </div>

                    <div class="col bg-secondary">
                        <h4 class="text-center p-5">TODO LIST</h4>
                        <tbody>
                            <!-- ciclo while per mostare tutta la tabella  -->
                            <ul class="text-center  list-group">
                                <?php while ($row = $results->fetch_assoc()) { ?>

                                    <li class="list-group-item list-group-item-action border-0"><?php echo $row['nome_todo'] ?></li>
                                    <div class="d-flex">
                                        <li class="list-group-item list-group-item-action border-0"><?php echo $row['data'] ?></li>

                                        <li class="list-group-item list-group-item-action border-0">
                                            <form action="index.php" method="POST">
                                                <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="status">
                                                <button type="submit" class="btn btn-outline-danger border-0">status<?php echo $row['status']?></button>
                                            </form>
                                        </li>

                                        <li class="list-group-item list-group-item-action border-0">
                                            <form action="index.php" method="POST">
                                                <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="important">
                                                <button type="submit" class="btn btn-outline-danger border-0">important<?php echo $row['important']?></button>
                                            </form>
                                        </li>

                                        <li class="list-group-item list-group-item-action border-0">
                                            <form action="index.php" method="POST">
                                                <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="delete">
                                                <button type="submit" class="btn btn-outline-danger border-0"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </li>
                                    </div>
                                <?php } ?>
                            </ul>
                    </div>

                    <div class="col bg-light">
                        <h4 class="text-center p-5">NEW TODO</h4>

                        <div class="card mx-auto">
                            <div class="card-body text-center ">
                                <form class="row g-3" action="index.php" method="POST">
                                    <div class="col-md-12">
                                        <label for="inputNewTodo" class="form-label">Nome todo</label>
                                        <input type="text" class="form-control" id="inputNewTodo" name="inputNewTodo">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="inputData" class="form-label">Data todo</label>
                                        <input type="text" class="form-control" id="inputData" name="inputData" placeholder="YY-MM-DD">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-paper-plane"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <!-- eventualità result <= 0 -->

                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-center p-3">Ciao <?php echo $_SESSION['username']; ?></h4>

                    <div class="d-flex align-items-center gap-3">
                        <!-- BOTTONE ALL TASKS -->
                        <form action="index.php" method="GET" class="border rounded">
                            <input type="hidden" type="text" value="1" name="all">
                            <button type="submit" class="btn border-0 "><i class="fa-solid fa-house btn btn-outline-secondary border-0 "></i> <span class="ms-3">All Tasks</span></button>
                        </form>


                        <!-- BOTTONE DI LOGOUT -->
                        <form action="logout.php" method="POST">
                            <input type="hidden" type="text" value="1" name="logout">
                            <button type="submit" class="btn btn-outline-secondary ">Logout</button>
                        </form>
                    </div>
                </div>


                <div class="card mx-auto">
                    <div class="card-body text-center bg-light">
                        <h4 class="text-center p-2 bg-danger text-white">ricerca impossibile, elemento non esistente</h4>
                        <h4 class="text-center p-5">NEW TODO</h4>

                        <div class="card mx-auto">
                            <div class="card-body text-center ">
                                <form class="row g-3" action="index.php" method="POST">
                                    <div class="col-md-12">
                                        <label for="inputNewTodo" class="form-label">Nome todo</label>
                                        <input type="text" class="form-control" id="inputNewTodo" name="inputNewTodo">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="inputData" class="form-label">Data todo</label>
                                        <input type="text" class="form-control" id="inputData" name="inputData" placeholder="YY-MM-DD">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-paper-plane"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
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