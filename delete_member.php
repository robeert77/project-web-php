<?php
    include_once "config/database.php";

    if (isset($_GET['id'])) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "DELETE FROM members WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$_GET['id']]);

        $uploadDir = 'pictures/';
        if (file_exists($uploadDir . $member['app_picture_name'])) {
            unlink($uploadDir . $member['app_picture_name']);
        }
    }
    
    header("Location: members.php");
    exit();
?>