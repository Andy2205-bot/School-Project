<?php
include('header.php');
include('sidebar.php');
require_once('../classes/schoolClass.php');

$response = array();
$sid = 0;
// pass connection to objects
$school = new SchoolClass($db);
if (isset($_GET['id'])) {

    $school->id = $_GET['id'];
    $sid=$_GET['id'];

    $schoolName = $school->GetSchoolDetailsById(  $school->id);

} else {
    # code...
}


?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit School</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="schools.php"> All Schools</a></li>
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


                        // set employee property values
                        $school->name = $_POST['txt_name'];
                        $school->id = $_POST['txt_id'];

                        $response = $school->UpdateSchoolDetails();
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
                        <h5>School Details</h5>
                    </div>
                    <div class="card-body">


                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                            <input type="hidden" name="txt_id" value="<?php echo $sid; ?>" class="form-control" id="inputNanme4">
                          
                                <label for="inputNanme4" class="form-label">School Name</label>
                                <input type="text" name="txt_name" value="<?php  echo (isset($schoolName)) ? $schoolName :''; ?>" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-sm-2">
                                <label for="inputNanme4" class="form-label">.</label>
                                <button type="submit" class="btn btn-primary form-control">Create</button>
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
