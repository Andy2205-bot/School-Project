<?php
include('header.php');
include('sidebar.php');
require_once('../classes/accountClass.php');

// get database connection
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Socials</h1>
        <!-- <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Tables</li>
      <li class="breadcrumb-item active">Data</li>
    </ol>
  </nav> -->
    </div><!-- End Page Title -->
    <br />
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Users</h5>


                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Accounts</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                // pass connection to objects
                                $accounts = new AccountClass($db);
                                $response=array();

                                $response = $accounts->GetAllUserAccounts($_SESSION['userId']);
                                foreach ($response as $key => $data) {
                                    echo " <tr><td><div class='news'>
                                    <div class='post-item clearfix'>
                                        <img src='assets/img/news-1.jpg' alt=''>
                                        <h4><a href='../scripts/createChatScript.php?id=".$data['userId']." & class='btn text-success'>".$data['fullname']."</a>"; echo ($data['isActive']==1) ? "    <span class='badge bg-success'><i class='bi bi-check-circle me-1'></i> Online</span><br>" : "    <span class='badge bg-secondary'><i class='bi bi-check-circle me-1'></i> Offline</span>";
                                        echo "</h4><p>"; echo $data['email']."<br>".$data['depart']." ( ".$data['role']." )</p>
                                    </div></td>
                                    </tr>";
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
