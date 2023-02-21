<?php
include('header.php');
include('sidebar.php');
require_once('../classes/staffClass.php');
require_once('../classes/department.php');
require_once('../classes/staffRolesClass.php');
require_once('../classes/humanTitle.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Staff Member</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="staff.php"> All Staff Members</a></li>
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
                        $staff = new StaffClass($db);
                        // set employee property values
                        $staff->titleId = $_POST['txt_titleId'];
                        $staff->firstnames = $_POST['txt_fnames'];
                        $staff->email = $_POST['txt_email'];
                        $staff->lastname = $_POST['txt_lastname'];
                        $staff->contacts = $_POST['txt_contacts'];
                        $staff->roleId = $_POST['txt_roleId'];
                        $staff->departId = $_POST['txt_departId'];

                        $response = $staff->addNewStaff();
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
                        <h5>Staff Details</h5>
                    </div>
                    <div class="card-body">


                        <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="col-sm-8">
                                <?php
                                // read the product categories from the database
                                $titles = new HumanTitleClass($db);
                                $stmt = $titles->read();
                                echo " <label for='inputNanme4' class='form-label'>Title</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_titleId' required>";
                                echo "<option>Select Title...</option>";

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>

                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Firstnames</label>
                                <input type="text" name="txt_fnames" class="form-control" id="inputNanme4" required>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Lastname</label>
                                <input type="text" name="txt_lastname" class="form-control" id="inputNanme4" required>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Email</label>
                                <input type="text" name="txt_email" class="form-control" id="inputNanme4" required>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Contacts</label>
                                <input type="text" name="txt_contacts" class="form-control" id="inputNanme4" required>
                            </div>

                            <div class="col-8">
                                <?php
                                // read the product categories from the database
                                $roles = new StaffRolesClass($db);
                                $stmt = $roles->read();
                                echo " <label for='inputNanme4' class='form-label'>Role</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_roleId' required>";
                                echo "<option>Select Role...</option>";

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>

                            <div class="col-8">
                                <?php
                                // read from the database
                                $departs = new StaffDepartmentClass($db);
                                $stmt = $departs->read();
                                echo " <label for='inputNanme4' class='form-label'>Department</label>";

                                // put them in a select drop-down
                                echo "<select class='form-control' name='txt_departId' required>";
                                echo "<option>Select Department...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>
                            <div class="col-5">
                                <button type="submit" class="btn btn-primary form-control">Add Staff</button>
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
