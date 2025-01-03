<?php
    include_once "config/database.php";
    include_once "includes/header.php"; 

    $database = new Database();
    $db = $database->getConnection();

    $total_members_query = "SELECT COUNT(*) AS nr_members FROM members";
    $total_members_stmt = $db->prepare($total_members_query);
    $total_members_stmt->execute();
    $total_members_row = $total_members_stmt->fetch(PDO::FETCH_ASSOC);
    $total_members = $total_members_row['nr_members'];

    $members_query = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM members ORDER BY first_name, last_name";
    $members_stmt = $db->prepare($members_query);
    $members_stmt->execute();

    $members = $members_stmt->fetchAll(PDO::FETCH_ASSOC);

    $profession_count_query = "SELECT profession, COUNT(*) AS count FROM members GROUP BY profession";
    $profession_count_stmt = $db->prepare($profession_count_query);
    $profession_count_stmt->execute();

    $professions = $profession_count_stmt->fetchAll(PDO::FETCH_ASSOC);

    $last_month_query = "
        SELECT CONCAT(first_name, ' ', last_name) AS full_name, created_at
        FROM members
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ORDER BY created_at DESC
    ";
    $last_month_stmt = $db->prepare($last_month_query);
    $last_month_stmt->execute();
    $recent_members = $last_month_stmt->fetchAll(PDO::FETCH_ASSOC);

    $recent_members_count = count($recent_members);

    $company_count_query = "SELECT company, COUNT(*) AS count FROM members GROUP BY company ORDER BY count DESC";
    $company_count_stmt = $db->prepare($company_count_query);
    $company_count_stmt->execute();

    $companies = $company_count_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="p-5 bg-body-tertiary rounded-3">
  <div class="container-fluid py-5">
    <h1 class="display-5 fw-bold">Welcome to Women in FinTech</h1>
      <p class="lead fs-5">Empowering women in financial technology through community and collaboration.</p>
      <hr class="my-4">
      <p>Join our community of professional women in FinTech.</p>
      <a class="btn btn-primary btn-lg" href="add_member.php" role="button" id="colored_button">Join Now</a>
  </div>
</div>

<div class="p-5 my-4 bg-body-tertiary rounded-3">
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <div class="col">
      <div class="card p-3 rounded-4">
        <h5 class="card-title text-decoration-underline" >Total members: <?php echo ($total_members); ?></h5>
        <div class="card-body ps-0">
          <a type="button" class="btn btn-primary" href="members.php">See all members</a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card p-3 rounded-4">
        <h5 class="card-title text-decoration-underline">Members by profession</h5>
        <div class="card-body ps-0 pt-0">
          <ul class="list-group">
            <?php foreach ($professions as $profession): ?>
              <li class="list-group-item border-0 ps-0 p-1">
                  <?php echo htmlspecialchars($profession['profession']) . ': <span class="text-body-secondary">' . $profession['count'] . '</span>'; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card p-3 rounded-4">
        <h5 class="card-title text-decoration-underline">New Members in Last Month: <?php echo $recent_members_count; ?></h5>
        <div class="card-body ps-0">
          <ul class="list-group">
            <?php foreach ($recent_members as $recent_member): ?>
              <li class="list-group-item border-0 ps-0 p-1">
                  <?php echo htmlspecialchars($recent_member['full_name']); ?>
                  <small class="text-body-secondary">Joined on <?php echo htmlspecialchars(date('d.m.Y', strtotime($recent_member['created_at']))); ?></small>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card p-3 rounded-4">
        <h5 class="card-title text-decoration-underline">Members by company</h5>
        <div class="card-body ps-0">
          <ul class="list-group">
            <?php foreach ($companies as $company): ?>
                <li class="list-group-item border-0 ps-0 p-1">
                    <?php echo htmlspecialchars($company['company']) . ': <span class="text-body-secondary">' . $company['count'] . '</span>'; ?>
                </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
    include_once "includes/footer.php";
?>