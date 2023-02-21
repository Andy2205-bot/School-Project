<?php
include('header.php');
include('sidebar.php');
require_once('../classes/notificationClass.php');

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Notice Management</h1>
        <br><br>
        <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item">Notifications</li>
      <li class="breadcrumb-item active"><a href="add-notification.php"> Add Notice</a></li>
    </ol>
  </nav>
    </div><!-- End Page Title -->
    <br />
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Notifications</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Created On</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // pass connection to objects
                                $notifications = new NotificationClass($db);

                                $stmt  = $notifications->GetAllUserNotifications($_SESSION['userId']);

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    //extract data
                                    extract($row);
                                    echo " <tr>
                                    <th scope='row'><a href='view-notification.php?id=".$id."'>".$title."</a></th>
                                    <td>".$created_on."</td>
                                    <td><a href='#'><span class='badge bg-danger'><i class='bi bi-check-circle me-1'></i> Delete</span></a></td></tr>";
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
