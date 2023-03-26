<?php
require_once 'db.php';
// Data Base Connection
$db = connect_pdo();
if ($_GET['id']) {
    $id = $_GET['id'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    // Check if Deleted
    if ($stmt->rowCount() > 0) {
        // DeleteImage
        $stmt = $db->prepare("SELECT image FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC)['image'];
        unlink("images/{$image}");
    }

    header("location:usersTable.php");
}

?>