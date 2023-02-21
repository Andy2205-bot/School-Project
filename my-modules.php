<?php
include('header.php');
include('sidebar.php');
require_once('../classes/lecturerModulesClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>My Modules</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Modules</li>
      <li class="breadcrumb-item active"><a href="add-lecturer-module.php"> Add Module</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Modules</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Module</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $modules = new LecturerModulesClass($db);

                                $stmt  = $modules->GetAllLecturerModulesByUserId($_SESSION['userId']);

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);
                                    echo " <tr>
                                    <th scope='row'><a href='edit-school.php?id=".$moduleId."'>".$moduleName." - ".$moduleCode."</a></th>
                                    <td>".$program."</td>
                                    <td>".$level."</td>
                                     <td><a href='module-students.php?id=".$moduleId."'>View Students</a></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
include('footer.php');
