<?php
include('header.php');
include('sidebar.php');
require_once('../classes/programClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Programs</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Programs</li>
      <li class="breadcrumb-item active"><a href="add-program.php"> Add new program</a></li>
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
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">School</th>
                                    <th scope="col">Created On</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $programs = new ProgramClass($db);

                                $stmt  = $programs->GetAllPrograms();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);

                                    echo " <tr>
                                    <th scope='row'>".$id."</th>
                                    <td>".$name."</td>
                                    <td>".$school."</td>
                                    <td>".$created_on."</td></tr>";
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
