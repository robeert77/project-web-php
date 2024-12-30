<?php
    echo "<link rel='stylesheet' type='text/css' href='css/aspect.css' />";

    include_once "config/database.php";
    include_once "includes/header.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $database = new Database();
        $db = $database->getConnection();

        $query = "INSERT INTO members
            (first_name, last_name, email, profession, company, expertise, linkedin_profile)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['profession'],
            $_POST['company'],
            $_POST['expertise'],
            $_POST['linkedin_profile']
            
        ]);
        $memberId = $db->lastInsertId();
      
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];

        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $pictureName = $memberId . '.' . $fileExtension;

        $uploadDir = 'pictures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        move_uploaded_file($fileTmpPath, $uploadDir . $pictureName);

        $query = "UPDATE members
            SET real_picture_name=?, app_picture_name=?
            WHERE id=?";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $fileName,
            $pictureName,
            $memberId
        ]);

        header("Location: members.php");
        exit();
    }
?>

<div class="form-container">
    <h2>Add New Member</h2>
    <form method="POST" enctype="multipart/form-data">
    <div class="form-floating mb-3">
            <input type="text" name="first_name" id="first_name" class="form-control" required>
            <label for="first_name">First Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="last_name" id="last_name" class="form-control" required>
            <label for="last_name">Last Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" name="email"  id="email" class="form-control" required>
            <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="profession" id="profession" class="form-control">
            <label for="profession">Profession</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="company" id="company" class="form-control">
            <label for="company">Company</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="expertise" id="expertise" class="form-control" rows="3"></textarea>
            <label for="expertise">Expertise</label>
        </div>
        <div class="form-floating mb-3">
            <input type="url" name="linkedin_profile" id="linkedin_profile" class="form-control">
            <label for="linkedin_profile">LinkedIn Profile</label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
            <label for="profile_picture">Profile Picture</label>
        </div>
        <button type="submit" class="btn btn-primary" id="colored_button">Add Member</button>
       
    </form> 
</div>

<?php
    include_once "includes/footer.php";
?>