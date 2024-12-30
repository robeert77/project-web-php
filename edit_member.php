<?php
    echo "<link rel='stylesheet' type='text/css' href='css/aspect.css' />";

    include_once "config/database.php";
    include_once "includes/header.php";
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $pictureName = $_GET['id'] . '.' . $fileExtension;

        $query = "UPDATE members
            SET first_name=?, last_name=?, email=?, profession=?,
            company=?, expertise=?, linkedin_profile=?, real_picture_name=?, app_picture_name=?
            WHERE id=?";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['profession'],
            $_POST['company'],
            $_POST['expertise'],
            $_POST['linkedin_profile'],
            $fileName,
            $pictureName,
            $_GET['id']
        ]);

        $uploadDir = 'pictures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (file_exists($uploadDir . $member['app_picture_name'])) {
            unlink($uploadDir . $member['app_picture_name']);
        }

        move_uploaded_file($fileTmpPath, $uploadDir . $pictureName);

        header("Location: members.php");
        exit();
    }
?>

<div class="form-container">
    <h2>Edit Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-floating mb-3">
            <input type="text" name="first_name" id="first_name" class="form-control"
            value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
            <label for="first_name">First Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="last_name" id="last_name" class="form-control"
            value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
            <label for="last_name">Last Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" name="email"  id="email" class="form-control"
            value="<?php echo htmlspecialchars($member['email']); ?>" required>
            <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="profession" id="profession" class="form-control"
            value="<?php echo htmlspecialchars($member['profession']); ?>">
            <label for="profession">Profession</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="company" id="company" class="form-control"
            value="<?php echo htmlspecialchars($member['company']); ?>">
            <label for="company">Company</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="expertise" id="expertise" class="form-control"><?php echo htmlspecialchars($member['expertise']); ?></textarea>
            <label for="expertise">Expertise</label>
        </div>
        <div class="form-floating mb-3">
            <input type="url" name="linkedin_profile" id="linkedin_profile" class="form-control"
            value="<?php echo htmlspecialchars($member['linkedin_profile']); ?>">
            <label for="linkedin_profile">LinkedIn Profile</label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
            <label for="profile_picture">Profile Picture</label>
        </div>

        <button type="submit" class="btn btn-primary" id="colored_button">Update Member</button>
    </form>
</div>

<?php
    include_once "includes/footer.php";
?>