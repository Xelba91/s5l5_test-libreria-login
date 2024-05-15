<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('database.php');
require_once('db_pdo.php');
require_once('userDTO.php');
$config = require_once('config.php');

use DB\DB_PDO as DB;


$PDOConn = DB::getInstance($config); 
$conn = $PDOConn->getConnection();

if (isset($_SESSION['session_id'])) {
    header('Location: pannello.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nomeutente'];
    $password = $_POST['password'];

    $userDTO = new UserDTO($conn);
    $user = $userDTO->getUserByUsername($username);

    if ($user && password_verify($password, $user['password']) && $user['ruolo'] === 'admin') {
        $_SESSION['session_id'] = $user['id'];
        $_SESSION['nomeutente'] = $user['nomeutente'];
        $_SESSION['ruolo'] = $user['ruolo']; 
        header('Location: pannello.php');
        exit;
    } elseif ($user && password_verify($password, $user['password']) && $user['ruolo'] === 'utente') {
        $_SESSION['session_id'] = $user['id'];
        $_SESSION['nomeutente'] = $user['nomeutente'];
        $_SESSION['ruolo'] = $user['ruolo']; 
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
<html lang="en" data-bs-theme="dark">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/assets/js/color-modes.js"></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <meta name="theme-color" content="#712cf9" />

   
    <link href="https://getbootstrap.com/docs/5.3/examples/sign-in/sign-in.css" rel="stylesheet" />


<style>
        body {
            background-image: url('https://www.potterandmore.com/images/mondo_magico/luoghi/hogwarts/biblioteca.jpg');
            background-size: cover; 
            background-repeat: no-repeat;
        }
        main {
            background-color: rgba(33, 37, 41, 0.8);
            border-radius: 10px;
            
        }
    .message {
        background-color: rgba(33, 37, 41, 0.8);
        border-radius: 10px;
        position: fixed;
        top: 10px; 
        left: 50%; 
        transform: translateX(-50%); 
        padding: 10px; 
        color: red;
    }
    footer {
        background-color: rgba(33, 37, 41, 0.8);
        border-radius: 10px;
        position: fixed;
        bottom: 20px; 
        left: 50%; 
        transform: translateX(-50%); 
        padding: 10px; 
        
    }
</style>
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <form action="index.php" method="POST">
       
            <h1 class="h2 mb-3 fw-normal text-center d-flex align-items-center justify-content-center"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-book me-1" viewBox="0 0 16 16">
  <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
</svg> Libreria Xelba <svg xmlns="http://www.w3.org/2000/svg" width="25" height="35" fill="currentColor" class="bi bi-book ms-1" viewBox="0 0 16 16">
  <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
</svg></h1>
            <h5 class="h5 mb-3 fw-normal text-center">LOGIN:</h5>

            <div class="form-floating ">
                <input type="text" class="form-control" id="floatingInput" placeholder="Nome utente" name="nomeutente"
                    value="" />
                <label for="floatingInput">Nome utente</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password"
                    value="" />
                <label for="floatingPassword">Password</label>
            </div>

            
            <button class="btn btn-primary w-100 mb-1 py-2" type="submit">
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
    <footer class="footer border-top border-white text-center text-white mt-5">
    <p className="m-0 py-3">&copy; 2024 Xelba Libreria</p>

    </footer>
    <script>
     
        <?php if(isset($_SESSION['error'])): ?>
      
            setTimeout(function() {
                <?php unset($_SESSION['error']); ?>
            }, 1000);
        <?php endif; ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>