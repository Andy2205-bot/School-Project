<!DOCTYPE html>
<html lang="en">

<?php

//starting a new session
session_start();

//check if the user is also logged in
if (isset($_SESSION['userId'])) {
    //redirect to dashboard
    echo "<script type='text/javascript'>;";
    echo "window.location.href='dashboard.php';";
    echo "</script>";
} else {
    // $msg = "User not logged in";
    // echo "<script type='text/javascript'>alert('$msg');";
    // echo "</script>";
}

require_once('../config/database.php');
require_once('../classes/accountClass.php');
// get database connection
$database = new Database();
$db = $database->getDbConnection();

//get user object
$user = new AccountClass($db);


?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login - Solusi Chat-Room</title>
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

</head>

<body>

    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="assets/img/logo.png" alt="">
                                    <span class="d-none d-lg-block">SU - Chat Room</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <!-- POST FORM CODE  -->

                                <?php
                                // if the form was submitted - PHP OOP CRUD Tutorial
                                if ($_POST) {
                                    // set employee property values
                                    $user->email = $_POST['txt_email'];
                                    $user->password = $_POST['txt_password'];

                                    $user_stm = $user->UserLogin($user->email);
                                    $user_data = $user_stm->fetch(PDO::FETCH_ASSOC);
                                    // create the product
                                    if ($user_stm->rowCount() > 0) {

                                        //verify password
                                        if (password_verify($user->password, $user_data['pass'])) {

                                            extract($user_data);
                                            $_SESSION['userId'] = $id;
                                            $_SESSION['userEmail'] = $email;
                                            $_SESSION['isUserStaff'] = $isUserStaff;
                                            $_SESSION['fnames'] = $firstnames . " " . $lastname;

                                            //update user status
                                            if($user->UpdateUserStatus($id,1)){}

                                            //check if the user is staff
                                            if ($isUserStaff == 1) {
                                                $_SESSION['role'] =$staffRole;
                                                $_SESSION['depart'] = $depart;
                                                $_SESSION['academicSessionId'] =2;

                                            } else {
                                                //create student sessions
                                                $_SESSION['role'] ="Student";
                                                $_SESSION['studentLevelId'] =$studentLevelId;
                                                $_SESSION['programId'] =$programId;
                                                $_SESSION['depart'] =$program." ( ".$studentsLevel." )";
                                                $_SESSION['academicSession'] =$academicSession;
                                                $_SESSION['academicSessionId'] =$academicSessionId;
                                            }


                                            // if ($_SESSION['upost'] != 1) {
                                            //     //redirect to employee
                                            //     echo "<script type='text/javascript'>;";
                                            //     echo "window.location.href='my_applications.php';";
                                            //     echo "</script>";
                                            // }
                                            //redirect to dashboard
                                            echo "<script type='text/javascript'>;";
                                            echo "window.location.href='dashboard.php';";
                                            echo "</script>";
                                        } else {
                                            //show alert wrong password
                                            echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                            Wrong email or password!
                                             <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                                              </div>";
                                        }
                                    }

                                    // if
                                    else {
                                        echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                        Account Not Found!
                                         <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                                          </div>";
                                    }
                                }
                                ?>

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Sign in</h5>
                                        <p class="text-center small">Enter your email & password</p>
                                    </div>

                                    <form class="row g-3 needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" name="txt_email" class="form-control" id="yourUsername" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="txt_password" class="form-control" id="yourPassword" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Don't have account? <a href="register.php">Create an account</a></p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="credits">
                                <!-- All the links in the footer should remain intact. -->
                                <!-- You can delete the links only if you purchased the pro version. -->
                                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                                Designed by <a href="https://bootstrapmade.com/"></a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.min.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>