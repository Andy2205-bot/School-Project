<?php
include('header.php');
include('sidebar.php');
require_once('../classes/accountClass.php');

$account = new AccountClass($db);

$response = $account->getUserProfileDetails($_SESSION['userId']);

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <!-- 
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
            </li> -->

                            <!-- <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
            </li> -->

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">


                            <div class="tab-pane fade show active pt-3" id="profile-change-password">
                                <?php
                                // if the form was submitted - PHP OOP CRUD Tutorial
                                if ($_POST) {
                                    // set employee property values
                                    $account->email = $_SESSION['userEmail'];
                                    $account->password = $_POST['txt_current_password'];
                                    $account->newPassword = $_POST['txt_new_password'];
                                    $uid = $_SESSION['userId'];
                                    $user_stm = $account->UserLogin($account->email);
                                    $user_data = $user_stm->fetch(PDO::FETCH_ASSOC);
                                    // create the product
                                    if ($user_stm->rowCount() > 0) {
                                        //check if new pass match
                                        if ($account->newPassword == $_POST['renewpassword']) {


                                            //verify password
                                            if (password_verify($account->password, $user_data['pass'])) {

                                                extract($user_data);
                                                //update user status
                                                if ($account->ChangePassword($uid, $account->newPassword)) {
                                                    echo "<div class='alert alert-success bg-success text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                                Password successfully changed!
                                                 <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                                                  </div>";
                                                } else {
                                                    echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                                Failed to changed password!
                                                 <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                                                  </div>";
                                                }
                                            } else {
                                                //show alert wrong password
                                                echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                            Wrong current pass password!
                                             <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                                              </div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>
                                        Passwords do not match!
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
                                <!-- Change Password Form -->
                                <form class="row g-3 needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="txt_current_password" type="password" class="form-control" id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="txt_new_password" type="password" class="form-control" id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                            <div class="tab-pane fade profile-overview" id="profile-overview">

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $response['fullname']; ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Department</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $response['depart']; ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Division</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $response['role']; ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $response['email']; ?></div>
                                </div>

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->
<?php
include('footer.php');
