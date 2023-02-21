<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');
require_once('../classes/programModulesClass.php');
require_once('../classes/notificationClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Notice</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="my-notifications.php"> Notifications</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <?php
                    // if the form was submitted - PHP OOP CRUD Tutorial
                    if ($_POST) {

                        $response = array();
                        // pass connection to objects
                        $notice = new NotificationClass($db);
                        // set employee property values
                        $notice->title = $_POST['txt_title'];
                        $notice->msg = $_POST['txt_msg'];
                        $notice->due = $_POST['txt_due'];
                        $notice->created_by = $_SESSION['fnames'];
                        $notice->userId = $_SESSION['userId'];
                        $notice->depart = $_SESSION['depart'];

                        $response = $notice->addNotification($notice->userId, $notice->depart, $notice->created_by);
                        // create the product
                        if ($response['error']) {
                            echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show text-center' role='alert'>" . $response['message'] . "
                      <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                        } else {
                            echo "<div class='alert alert-success bg-success text-light border-0 alert-dismissible fade show text-center' role='alert'>" . $response['message'] . "
                  <button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                        }
                    }
                    ?>
                    <div class="card-header">
                        <h5>Notice Details</h5>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Title</label>
                                <input type="text" name="txt_title" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Due</label>
                                <input type="date" name="txt_due" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Message</label>
                                <textarea id="chatMessage" name="txt_msg" rows="2" cols="1" class="form-control"></textarea>
                            </div>

                            <div class="col-sm-5">
                                <button type="submit" class="btn btn-primary form-control">Add Notification</button>
                            </div>

                        </form><!-- Vertical Form -->
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
include('footer.php');
