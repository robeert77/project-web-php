<?php
    include_once "config/database.php";
    include_once "includes/header.php";
    
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT * FROM members";

    if (!empty($_GET['order_by'])) {
        $allowed_columns = ['name', 'data_inscrierii']; // Coloane permise pentru sortare
        $order_by = $_GET['order_by'];
        
        // Validare pentru a preveni SQL Injection
        if (in_array($order_by, $allowed_columns)) {
            if ($order_by === 'name') {
                $query .= " ORDER BY first_name ASC"; // Presupunem că sortăm după first_name
            } elseif ($order_by === 'data_inscrierii') {
                $query .= " ORDER BY created_at ASC"; // Sortare după data
            }
        }
    }
    $stmt = $db->prepare($query);
    $stmt->execute();
?>

<h2>Members Directory</h2>

<div class="input-group mb-3">
    <label class="input-group-text" for="sortSelect">Sort by</label>
    <select class="form-select" id="sortSelect" onchange="applySort()">
        <option value="name" <?php echo isset($_GET['order_by']) && $_GET['order_by'] === 'name' ? 'selected' : ''; ?>>Name</option>
        <option value="data_inscrierii" <?php echo isset($_GET['order_by']) && $_GET['order_by'] === 'data_inscrierii' ? 'selected' : ''; ?>>Date</option>
    </select>
</div>

<script>
    // Functie pentru a aplica sortarea
    function applySort() {
        const sortSelect = document.getElementById('sortSelect');
        const selectedValue = sortSelect.value;
        const currentUrl = new URL(window.location.href);
        
        if (selectedValue) {
            currentUrl.searchParams.set('order_by', selectedValue);
        } else {
            currentUrl.searchParams.delete('order_by');
        }

        window.location.href = currentUrl;
    }
</script>

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