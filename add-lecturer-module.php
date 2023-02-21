<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');
require_once('../classes/lecturerModulesClass.php');
require_once('../classes/studentLevel.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add New Module</h1>
        <br><br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="my-modules.php"> My Modules</a></li>
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
                        $module = new LecturerModulesClass($db);
                        // set employee property values
                        $module->moduleId = $_POST['txt_moduleId'];
                        $module->lecturerId = $_SESSION['userId'];

                        $response = $module->addNewLectureModule($_SESSION['userId']);
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
                                <label for="inputNanme4" class="form-label">Program</label>
                                <?php
                                // read the product categories from the database
                                $program = new ProgramClass($db);
                                $stmt = $program->read();

                                // put them in a select drop-down
                                echo "<select class='form-control' id='program' name='txt_programId' required>";
                                echo "<option>Select Program...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Level</label>
                                <?php
                                // read the product categories from the database
                                $level = new StudentLevelClass($db);
                                $stmt = $level->read();

                                // put them in a select drop-down
                                echo "<select class='form-control' id='level' name='txt_levelId' required>";
                                echo "<option>Select Program...</option>";

                                while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_category);
                                    echo "<option value='{$id}'>{$name}</option>";
                                }

                                echo "</select>";
                                ?>
                            </div>
                            <div class="col-sm-8">
                                <label for="inputNanme4" class="form-label">Module</label>
                                <select class='form-control' id="moduleOptions" name='txt_moduleId' required>
                                </select>
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

<script>
    var programId = 0;
    var levelId = 0;
    $(document).ready(function() {
        $('#program').change(function() {
            programId = $(this).find("option:selected").attr('value');
          //  alert("program Id: " + programId);
            //make ajax request to load data
            getProgramModules()
        });
        $('#level').change(function() {
            levelId = $(this).find("option:selected").attr('value');
           // alert("levelId: " + levelId);
            //make ajax request to load data
            getProgramModules();
        });
    });

    //function to make ajax request get program modules 
    function getProgramModules() {

        if (programId < 1 || levelId < 1) {
            //do nothing
        } else {
            //reset list
            $('#moduleOptions').html('');
            //make ajax request
            $.ajax({
                type: "GET",
                url: "../scripts/get-program-modules-script.php?pid=" + programId+"&lid="+levelId,
                dataType: "json",
                processData: false
            }).done(function(data) {
                let jsonData = data;
                var jsonLength = jsonData.length;
                console.log(jsonData);

                var html = "";
                $('#moduleOptions').html = '';
                $('#moduleOptions').append('<option value="">Please select...</option>');


                for (var i = 0; i < jsonLength; i++) {
                    var result = jsonData[i];
                    var id = Number(result.programModuleId)
                    var moduleName = (result.moduleName)+ " - " + (result.moduleCode);

                    //html += '<option value='+id+'>'+moduleName+'</option>';
              
                    //console.log(html);
                    $('#moduleOptions').append('<option value="'+ id +'">' + moduleName + '</option>');
       
                   // $('#moduleOptions').append(html);
                }
               //$('#moduleOptions').html(html);

            });
        }
    }
</script>
<?php
include('footer.php');
