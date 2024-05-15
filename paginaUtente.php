<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('database.php');
require_once('db_pdo.php');
require_once('userDTO.php');
require_once('Book.php');
$config = require_once('config.php');

use DB\DB_PDO as DB;

// Connessione al database
$PDOConn = DB::getInstance($config);
$conn = $PDOConn->getConnection();
$userDTO = new UserDTO($conn);

if (!isset($_SESSION['session_id'])) {
    // Reindirizza l'utente alla pagina di login
    header('Location: index.php');
    exit;
}

// Creare la tabella dei libri se non esiste
$query = "
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL
)";
$conn->exec($query);

// Funzioni per gestire i libri
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        try {
            switch ($action) {
                case 'add':
                    $title = $_POST['title'];
                    $author = $_POST['author'];
                    $image_url = $_POST['image_url'];
                
                    // Validate inputs
                    if (!empty($title) && !empty($author) && !empty($image_url)) {
                        $stmt = $conn->prepare("INSERT INTO books (title, author, image_url) VALUES (?, ?, ?)");
                        $stmt->execute([$title, $author, $image_url]);
                
                        // Show success message
                        echo '<script>alert("Libro aggiunto con successo!");</script>';
                    } else {
                        throw new Exception("All fields are required.");
                    }
                    break;
                    case 'edit':
                        $id = $_POST['id'];
                        $title = $_POST['title'];
                        $author = $_POST['author'];
                        $image_url = $_POST['image_url'];
                    
                        // Validate inputs
                        if (!empty($id) && !empty($title) && !empty($author) && !empty($image_url)) {
                            $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, image_url = ? WHERE id = ?");
                            $stmt->execute([$title, $author, $image_url, $id]);
                            
                            // Show success message
                            echo '<script>alert("Libro modificato con successo!");</script>';
                        } else {
                            throw new Exception("All fields are required.");
                        }
                        break;
                case 'delete':
                    $id = $_POST['id'];

                    // Validate input
                    if (!empty($id)) {
                        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
                        $stmt->execute([$id]);
                    } else {
                        throw new Exception("ID is required.");
                    }
                    break;
                default:
                    throw new Exception("Invalid action.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Recuperare tutti i libri dal database
$stmt = $conn->query("SELECT * FROM books");
$booksData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convertire i dati dei libri in oggetti Book
$books = array();
foreach ($booksData as $bookData) {
    $books[] = new Book($bookData['id'], $bookData['title'], $bookData['author'], $bookData['image_url']);
}
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Gestione Libri</title>
    <style>
        #addBookForm {
            display: none; /* Nascondi il form inizialmente */
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Utente <?php echo htmlspecialchars($_SESSION['nomeutente']); ?></a>
            <a href="logout.php" class="btn btn-outline-success">Logout</a>
        </div>
    </nav>
    <div class="container my-5">
        <h1 class="text-center">Ciao <?php echo htmlspecialchars($_SESSION['nomeutente']); ?></h1>
        
        <!-- Pulsante per aprire il form di aggiunta -->
        <button type="button" class="btn btn-primary mb-3" onclick="toggleAddBookForm()">Aggiungi un nuovo libro</button>
        
        <!-- Form per aggiungere un nuovo libro (inizialmente nascosto) -->
        <div id="addBookForm">
            <h2>Aggiungi un nuovo libro</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Autore</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">URL Immagine</label>
                    <input type="text" class="form-control" id="image_url" name="image_url" required>
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi</button>
            </form>
        </div>

        <!-- Tabella dei libri -->
        <h2 class="mt-5 text-center">Libri</h2>
        <!-- Qui va inserita la tabella dei libri -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titolo</th>
                    <th>Autore</th>
                    <th>Immagine</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book->getId()); ?></td>
                    <td><?php echo htmlspecialchars($book->getTitle()); ?></td>
                    <td><?php echo htmlspecialchars($book->getAuthor()); ?></td>
                    <td><img src="<?php echo htmlspecialchars($book->getImageUrl()); ?>" alt="Immagine Libro" width="50"></td>
                    <td>
                        <!-- Form per modificare un libro -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($book->getId()); ?>">
                            <input type="text" name="title" value="<?php echo htmlspecialchars($book->getTitle()); ?>" required>
                            <input type="text" name="author" value="<?php echo htmlspecialchars($book->getAuthor()); ?>" required>
                            <input type="text" name="image_url" value="<?php echo htmlspecialchars($book->getImageUrl()); ?>" required>
                            <button type="submit" class="btn btn-warning btn-sm">Modifica</button>
                        </form>
                        <!-- Form per eliminare un libro -->
                        <form method="POST" class="d-inline" onsubmit="return confirmDelete()">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book->getId()); ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>




    </div>
    <script>
    function confirmDelete() {
        return confirm("Sei sicuro di voler eliminare questo libro?");
    }
</script>

    <script>
    function toggleAddBookForm() {
        var addBookForm = document.getElementById("addBookForm");
        if (addBookForm.style.display === "none" || addBookForm.style.display === "") {
            addBookForm.style.display = "block";
        } else {
            addBookForm.style.display = "none";
        }
    }
    
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

