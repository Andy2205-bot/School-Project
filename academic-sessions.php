<?php
include('header.php');
include('sidebar.php');
require_once('../classes/schoolClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Academic Sessions</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Schools</li>
      <li class="breadcrumb-item active"><a href="add-school.php"> Add Session</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    
                    <div class="card-body">
                        <h5 class="card-title">All Schools</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">School / Faculty</th>
                                    <th scope="col">Created On</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $schools = new SchoolClass($db);

                                $stmt  = $schools->GetAllSchools();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);

                                    echo " <tr>
                                    <td><a href='edit-school.php?id=".$id."'>".$sname."</a></td>
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
