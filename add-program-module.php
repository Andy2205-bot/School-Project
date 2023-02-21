<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');
require_once('../classes/programModulesClass.php');
require_once('../classes/studentLevel.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Program Module</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="modules.php"> All Programs Modules</a></li>
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
                        $module = new ProgramModulesClass($db);
                        // set employee property values
                        $module->moduleName = $_POST['txt_moduleName'];
                        $module->moduleCode = $_POST['txt_moduleCode'];
                        $module->programId = $_POST['txt_programId'];
                        $module->levelId = $_POST['txt_levelId'];

                        $response = $module->addNewProgramModule();
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
                        <h5>Program Module Details</h5>
                    </div>
                    <div class="card-body">


                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Module Name</label>
                                <input type="text" name="txt_moduleName" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Module Code</label>
                                <input type="text" name="txt_moduleCode" class="form-control" id="inputNanme4">
                            </div>
                            <div class="col-sm-8">
                                <?php
                                // read the product categories from the database
                                $program = new ProgramClass($db);
                                $stmt = $program->read();

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
                            <div class="col-sm-8">
                                <?php
                                // read the product categories from the database
                                $level = new StudentLevelClass($db);
                                $stmt = $level->read();

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_levelId' required>";
                                echo "<option>Select Level...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>

                            <div class="col-sm-5">
                                <button type="submit" class="btn btn-primary form-control">Add Program Module</button>
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
