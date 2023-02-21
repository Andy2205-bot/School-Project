<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programModulesClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Programs Modules</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Programs Modules</li>
      <li class="breadcrumb-item active"><a href="add-program-module.php"> Add New Module</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Programs</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Level</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $modules = new ProgramModulesClass($db);

                                $stmt  = $modules->GetAllProgramsModules();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);

                                    echo " <tr>
                                    <td>".$moduleName." - ".$moduleCode."</td>
                                    <td>".$program."</td>
                                    <td>".$level."</td></tr>";
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
