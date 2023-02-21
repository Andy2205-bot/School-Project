<?php
include('header.php');
include('sidebar.php');
require_once('../classes/studentClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Students</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Students</li>
      <li class="breadcrumb-item active"><a href="add-student.php"> Add Student</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    
                    <div class="card-body">
                        <h5 class="card-title">All Students</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Reg No</th>                                
                                    <th scope="col">Fullname</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Level</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $students = new StudentClass($db);

                                $stmt  = $students->GetAllStudents();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);

                                    echo " <tr>
                                    <td><a href='edit-student.php?id=".$studentId."'>".$regNum."</a></td>
                                    <td>".$firstnames." ".$lastname."</td>
                                    <td>".$gender."</td>
                                    <td>".$email."</td>
                                    <td>".$program."</td>
                                    <td>".$level ."</td></tr>";
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
