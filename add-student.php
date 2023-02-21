<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');
require_once('../classes/studentClass.php');
require_once('../classes/studentLevel.php');
require_once('../classes/genderClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Student</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="students.php"> All Students</a></li>
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
                        $student = new StudentClass($db);
                        // set employee property values
                        $student->regNum = $_POST['txt_regnum'];
                        $student->firstnames = $_POST['txt_fnames'];
                        $student->lastname = $_POST['txt_lastname'];
                        $student->genderId = $_POST['txt_genderId'];
                        $student->programId = $_POST['txt_programId'];
                        $student->levelId = $_POST['txt_levelId'];

                        $msg = $student->firstnames;
                        echo "<script type='text/javascript'>alert('$msg');";
                        echo "</script>";

                        $response = $student->addNewStudent();
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
                        <h5>Student Details</h5>
                    </div>
                    <div class="card-body">


                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Reg Number</label>
                                <input type="text" name="txt_regnum" class="form-control" id="inputNanme4" required>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Firstnames</label>
                                <input type="text" name="txt_fnames" class="form-control" id="inputNanme4" required>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Lastname</label>
                                <input type="text" name="txt_lastname" class="form-control" id="inputNanme4" required>
                            </div>

                            <div class="col-8">
                                <?php
                                // read the product categories from the database
                                $gender = new GenderClass($db);
                                $stmt = $gender->read();
                                echo " <label for='inputNanme4' class='form-label'>Gender</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_genderId' required>";
                                echo "<option>Select Gender...</option>";

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>

                            <div class="col-8">
                                <?php
                                // read the product categories from the database
                                $program = new ProgramClass($db);
                                $stmt = $program->read();
                                echo " <label for='inputNanme4' class='form-label'>Program</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_programId' required>";
                                echo "<option>Select Program...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>

                            <div class="col-8">
                                <?php
                                // read the product categories from the database
                                $level = new StudentLevelClass($db);
                                $stmt = $level->read();

                                echo " <label for='inputNanme4' class='form-label'>Level</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_levelId' required>";
                                echo "<option>Select school...</option>";

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>
                            <div class="col-5">
                                <button type="submit" class="btn btn-primary form-control">Add Student</button>
                            </div>
                            <br>
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
