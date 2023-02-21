<?php
include('header.php');
include('sidebar.php');
require_once('../classes/staffClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Staff Members</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Staff Members</li>
      <li class="breadcrumb-item active"><a href="add-staff-members.php"> Add Staff</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    
                    <div class="card-body">
                        <h5 class="card-title">All Staff Members</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>                              
                                    <th scope="col">Fullname</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">role</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $staff = new StaffClass($db);

                                $stmt  = $staff->GetAllStaffMembers();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);

                                    echo " <tr>
                                    <td><a href='edit-staff-member.php?id=".$staffId."'>".$title.". ".$firstnames." ".$lastname."</a></td>
                                    <td>".$email."</td>
                                    <td>".$depart."</td>
                                    <td>".$role."</td></tr>";
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
