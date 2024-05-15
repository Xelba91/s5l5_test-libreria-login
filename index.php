<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('database.php');
require_once('db_pdo.php');
require_once('userDTO.php');
$config = require_once('config.php');

use DB\DB_PDO as DB;

//mi connetto al database
$PDOConn = DB::getInstance($config); 
$conn = $PDOConn->getConnection();
// verifica se l'utente ha gia effettuato l'accesso
if (isset($_SESSION['session_id'])) {
    header('Location: pannello.php');
    exit;
}
// controllo utente nel database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nomeutente'];
    $password = $_POST['password'];

    $userDTO = new UserDTO($conn);
    $user = $userDTO->getUserByUsername($username);

    if ($user && password_verify($password, $user['password']) && $user['ruolo'] === 'admin') {
        $_SESSION['session_id'] = $user['id'];
        $_SESSION['nomeutente'] = $user['nomeutente'];
        $_SESSION['ruolo'] = $user['ruolo']; // memorizza il ruolo dell'utente in sessione
        header('Location: pannello.php');
        exit;
    } elseif ($user && password_verify($password, $user['password']) && $user['ruolo'] === 'utente') {
        $_SESSION['session_id'] = $user['id'];
        $_SESSION['nomeutente'] = $user['nomeutente'];
        $_SESSION['ruolo'] = $user['ruolo']; // memorizza il ruolo dell'utente in sessione
        header('Location: paginaUtente.php');
        exit;
    }
    else {
        $_SESSION['error'] = "Credenziali non valide ";
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/assets/js/color-modes.js"></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
    <meta name="generator" content="Hugo 0.122.0" />
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <meta name="theme-color" content="#712cf9" />

    <link rel="stylesheet" href="css/style.css" />
    <link href="https://getbootstrap.com/docs/5.3/examples/sign-in/sign-in.css" rel="stylesheet" />
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <form action="index.php" method="POST">
            <h1 class="h3 mb-3 fw-normal text-center">LOGIN</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="Nome utente" name="nomeutente"
                    value="" />
                <label for="floatingInput">Nome utente</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password"
                    value="" />
                <label for="floatingPassword">Password</label>
            </div>

            
            <button class="btn btn-primary w-100 py-2" type="submit">
                Accedi
            </button>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger my-3" role="alert">' . $_SESSION['error'] . '</div>';
            }
            ?>
        </form>
        <div class="mt-3">Non hai un account? <a href="register.php">Registrati</a></div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>