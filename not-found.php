<?php
include('header.php');
include('sidebar.php');
require_once('../classes/schoolClass.php');

?>
  <main>
    <div class="container">
      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h3>GROUP NOT FOUND!</h3>
        <a class="btn" href="dashboard.php">Back to home</a>
        <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Designed by <a href="#">Adak</a>
        </div>
      </section>

    </div>
  </main><!-- End #main -->
<?php
include('footer.php');
