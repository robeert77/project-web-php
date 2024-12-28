<?php
    echo "<link rel='stylesheet' type='text/css' href='css/aspect.css' />";

    include_once "config/database.php";
    include_once "includes/header.php";
    
    $database = new Database();
    $db = $database->getConnection();

    $total_rows_query = "SELECT COUNT(*) AS nr_members FROM members";
    $stmt = $db->prepare($total_rows_query);
    $stmt->execute();
    $total_pages_result = $stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM members";
    $current_page = !empty($_GET['current_page']) ? $_GET['current_page'] : 0;
    $limit = 6;
    $offset = $current_page * $limit;

    $total_pages = ceil($total_pages_result['nr_members'] / $limit);

    if (!empty($_GET['profession'])) {
        $query .= ' WHERE profession LIKE :profession';
    }
    
    if (!empty($_GET['order_by'])) {
        $allowed_columns = ['first_name', 'created_at'];
        $order_by = $_GET['order_by'];
        
        if (in_array($order_by, $allowed_columns)) {
            $query .= " ORDER BY " . $order_by . " ASC";
        }
    }

    $query .= ' LIMIT :limit OFFSET :offset';
  
    $stmt = $db->prepare($query);
    if (!empty($_GET['profession'])) {
        $stmt->bindValue(':profession', '%'. $_GET['profession'] . '%');
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute()
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
        <div class="col-md-3">
            <div class="form-floating">
                <input class="form-control" id="profession" name="profession" type="text" value="<?php echo !empty($_GET['profession']) ? $_GET['profession'] : ''; ?>"/>
                <label for="profession">Profession</label>
            </div>
        </div>
    </div>

    <input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
    <button type="submit" class="btn btn-primary">Apply</button>
    
</form>

<hr>

<h2>Members Directory</h2>

<div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4">
            <div class="card mb-3" id="card">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?php echo 'pictures/' . $row['app_picture_name']; ?>" class="img-fluid rounded-start" alt="Profile Picture" >
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h3>
                            <hr>
                            <p class="card-text mb-2"><strong>Profession: </strong><?php echo htmlspecialchars($row['profession']); ?></p>
                            <p class="card-text mb-2"><strong>Company: </strong><?php echo htmlspecialchars($row['company']); ?></p>
                        </div>
                        <div class="d-flex justify-content-end align-items-center gap-2 px-3">
                            <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                            </a>
                            <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<nav aria-label="...">
    <ul class="pagination">
        <li class="page-item <?php echo ($current_page > 0) ? '' : 'disabled'; ?>">
            <a class="page-link" href="<?php echo '?current_page=' . ($current_page - 1); ?>">Previous</a>
        </li>

        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <li class="page-item <?php echo ($current_page == $i - 1) ? 'active' : ''; ?>" aria-current="page">
                <a class="page-link"  href="<?php echo '?current_page=' . $i - 1; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
        
        <li class="page-item <?php echo ($current_page < $total_pages - 1) ? '' : 'disabled'; ?>">
            <a class="page-link" href="<?php echo '?current_page=' . ($current_page + 1); ?>">Next</a>
        </li>
    </ul>
</nav>

<?php
    include_once "includes/footer.php"
?>