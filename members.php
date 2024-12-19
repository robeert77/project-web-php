<?php
    include_once "config/database.php";
    include_once "includes/header.php";
    
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT * FROM members";

    if (!empty($_GET['order_by'])) {
        $allowed_columns = [
            'name' => 'first_name', 
            'data_inscrierii' => 'created_at'
        ];
        $order_by = $_GET['order_by'];
        
        if (array_key_exists($order_by, $allowed_columns)) {
            $query .= " ORDER BY " . $allowed_columns[$order_by] . " ASC";
        }
    }
    $stmt = $db->prepare($query);
    $stmt->execute();
?>

<h2>Members Directory</h2>

<form method="GET" action="" class="input-group mb-3">
    <label class="input-group-text" for="sortSelect">Sort by</label>
    <select class="form-select" id="sortSelect" name="order_by" onchange="this.form.submit()">
        <option value="name" <?php echo !empty($order_by) && $order_by === 'name' ? 'selected' : ''; ?>>Name</option>
        <option value="data_inscrierii" <?php echo !empty($order_by) && $order_by === 'data_inscrierii' ? 'selected' : ''; ?>>Date</option>
    </select>
</form>

<div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4">
            <div class="card member-card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h5>
                    <p class="card-text">
                        <strong>Profession:</strong> <?php echo htmlspecialchars($row['profession']); ?><br>
                        <strong>Company:</strong> <?php echo htmlspecialchars($row['company']); ?>
                    </p>
                    <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php
    include_once "includes/footer.php"
?>