<?php
    include_once "config/database.php";
    include_once "includes/header.php";
    
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT * FROM members";

    if (!empty($_GET['order_by'])) {
        $allowed_columns = ['first_name', 'created_at'];
        $order_by = $_GET['order_by'];
        
        if (in_array($order_by, $allowed_columns)) {
            $query .= " ORDER BY " . $order_by . " ASC";
        }
    }

    $stmt = $db->prepare($query);
    $stmt->execute();
?>

<h4>Filters</h4>

<form method="GET" class="mb-3">
    <div class="row my-3">
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select" id="sortSelect" name="order_by">
                    <option value="" <?php echo empty($order_by) ? 'selected' : ''; ?>>Default</option>
                    <option value="first_name" <?php echo !empty($order_by) && $order_by === 'first_name' ? 'selected' : ''; ?>>Name</option>
                    <option value="created_at" <?php echo !empty($order_by) && $order_by === 'created_at' ? 'selected' : ''; ?>>Date</option>
                </select>
                <label for="sortSelect">Sort by</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Apply</button>
</form>

<hr>

<h2>Members Directory</h2>

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