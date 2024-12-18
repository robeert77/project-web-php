<?php
    include_once "config/database.php";

    if (isset($_GET['id'])) {
        $database = new Database();
        $db = $database->getConnection();
        $query = "DELETE FROM members WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$_GET['id']]);
    }
    
    header("Location: members.php");
    exit();
?>