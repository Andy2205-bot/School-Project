<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');
require_once('../classes/schoolClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Program</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="programs.php"> All Programs</a></li>
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
                        $program = new ProgramClass($db);
                        // set employee property values
                        $program->name = $_POST['txt_name'];
                        $program->schoolId = $_POST['txt_schoolId'];

                        $msg = $program->name;
                        echo "<script type='text/javascript'>alert('$msg');";
                        echo "</script>";

                        $response = $program->addNewProgram($program->name);
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
                        <h5>Program Details</h5>
                    </div>
                    <div class="card-body">


                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Program Name</label>
                                <input type="text" name="txt_name" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-8">
                                <?php
                                // read the product categories from the database
                                $school = new SchoolClass($db);
                                $stmt = $school->read();

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_schoolId' required>";
                                echo "<option>Select school...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$sname}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary form-control">Create</button>
                            </div>
                            <!--<div class="col-12">
                                <label for="inputAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                            </div> -->

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
