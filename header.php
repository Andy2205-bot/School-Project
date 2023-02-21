<!DOCTYPE html>
<html lang="en">
<?php
//   <!-- ======= Header ======= -->
session_start();

//check if the user is also logged in
if (isset($_SESSION['userId'])) {
  //redirect to dashboard
  require_once('../config/database.php');
  require_once('../classes/notificationClass.php');
  // get database connection
  $database = new Database();
  $db = $database->getDbConnection();
} else {
  //  $msg="User not logged in";
  //   echo "<script type='text/javascript'>alert('$msg');";
  //   echo "</script>";
  echo "<script type='text/javascript'>;";
  echo "window.location.href='login.php';";
  echo "</script>";
}

?>


<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SU - Chat Room</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <script src="assets/js/jquery.min.js"></script>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">SU - Chat Room</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">
          <?php
          // pass connection to objects
          $notifications = new NotificationClass($db);

          $stmt  = $notifications->GetAllNotifications();

          echo "<a class='nav-link nav-icon' href='#' data-bs-toggle='dropdown'>
          <i class='bi bi-bell'></i>
          <span class='badge bg-primary badge-number'>" . $stmt->rowCount() . "</span>
        </a><ul class='dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications'> <li class='dropdown-header'>
        You have " . $stmt->rowCount() . " new notifications
        <a href='#'><span class='badge rounded-pill bg-primary p-2 ms-2'>View all</span></a>
      </li>
      <li>
        <hr class='dropdown-divider'>
      </li>";

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);
            echo "<li class='notification-item'>
            <i class='bi bi-exclamation-circle text-warning'></i>
            <div>
              <h4>".$depart."</h4>
              <p>".$title."</p>
              <p>".$created_on."</p>
            </div>
          </li> <li>
          <hr class='dropdown-divider'>
        </li>";
          }
          echo "</ul></li>";
          ?>
        <!-- <li class="dropdown-footer">
          <a href="#">Show all notifications</a>
        </li> -->

      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['fnames']; ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $_SESSION['fnames']; ?></h6>
            <span><?php echo $_SESSION['depart'] . " ( " . $_SESSION['role'] . " )"; ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="profile.php">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->