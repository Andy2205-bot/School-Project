  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Chat Room</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <?php
      require_once('../classes/moduleClass.php');

      $modules = new ModuleClass($db);

      if ($_SESSION['isUserStaff'] == 0) {
        echo " <li class='nav-heading'>Modules Groups</li>
            <hr>
            <!-- start Modules Nav -->
            <li class='nav-item'>
              <a class='nav-link collapsed' data-bs-target='#modules-nav' data-bs-toggle='collapse' href='#'>
                <i class='bi bi-journal-text'></i><span>Modules</span><i class='bi bi-chevron-down ms-auto'></i>
              </a>
              <ul id='modules-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>";
        //load modules groups
        $stmt  = $modules->GetAllModulesByUserId($_SESSION['programId'], $_SESSION['studentLevelId']);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          //extract data
          extract($row);
          echo "<li><a href='module-chat-room.php?id=" . $programModuleId . "'>
              <i class='bi bi-circle'></i>
              <span>" . $moduleName . "</span>
              </a>
            </li>";
        }
      }

      if ($_SESSION['role'] == "Lecturer") {
        echo " <li class='nav-heading'>Lecturer Panel</li>
        <hr>
        <!-- start Modules Nav -->
        <li class='nav-item'>
          <a class='nav-link collapsed' data-bs-target='#modules-nav' data-bs-toggle='collapse' href='#'>
            <i class='bi bi-journal-text'></i><span>My Modules</span><i class='bi bi-chevron-down ms-auto'></i>
          </a>
          <ul id='modules-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>";

        echo "<li><a href='my-modules.php'>
              <i class='bi bi-circle'></i>
              <span>Modules</span>
              </a>
            </li>";
      }
      if ($_SESSION['role'] != "Lecturer" && $_SESSION['role'] != "Student") {
        echo " <li class='nav-heading'>Notifications Panel</li>
        <hr>
        <!-- start notifications Nav -->
        <li class='nav-item'>
          <a class='nav-link collapsed' data-bs-target='#modules-nav' data-bs-toggle='collapse' href='#'>
            <i class='bi bi-journal-text'></i><span>Manage Notifications</span><i class='bi bi-chevron-down ms-auto'></i>
          </a>
          <ul id='modules-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>";

        echo "<li><a href='my-notifications.php'>
              <i class='bi bi-circle'></i>
              <span>Notifications</span>
              </a>
            </li>";
      }

      ?>
    </ul>

    <?php

    ?>
    </li><!-- End Forms Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="profile.php">
        <i class="bi bi-person"></i>
        <span>Profile</span>
      </a>
    </li><!-- End Profile Page Nav -->
    <hr>
    <?php
    if ($_SESSION['role'] == "SysAdmin") {

      //admin panel
      echo "<li class='nav-heading'>Administrator</li><hr>
      <!-- Schools Nav -->
      <li class='nav-item'>
        <a class='nav-link collapsed' data-bs-target='#schools-nav' data-bs-toggle='collapse' href='#'>
          <i class='bi bi-journal-text'></i><span>Schools/Facults</span><i class='bi bi-chevron-down ms-auto'></i>
        </a>
        <ul id='schools-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>
          <li>
            <a href='schools.php'>
              <i class='bi bi-circle'></i><span>Schools</span>
            </a>
          </li>
        </ul>
      </li><!-- End schools Nav -->

      <!-- Programs Nav -->
      <li class='nav-item'>
        <a class='nav-link collapsed' data-bs-target='#programs-nav' data-bs-toggle='collapse' href='#'>
          <i class='bi bi-journal-text'></i><span>Programs</span><i class='bi bi-chevron-down ms-auto'></i>
        </a>
        <ul id='programs-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>
          <li>
            <a href='programs.php'>
              <i class='bi bi-circle'></i><span>Programs</span>
            </a>
          </li>
        </ul>
      </li><!-- End Programs Nav -->

      <!-- Program Modules Nav -->
      <li class='nav-item'>
        <a class='nav-link collapsed' data-bs-target='#program-modules-nav' data-bs-toggle='collapse' href='#'>
          <i class='bi bi-journal-text'></i><span>Program Modules</span><i class='bi bi-chevron-down ms-auto'></i>
        </a>
        <ul id='program-modules-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>
          <li>
            <a href='modules.php'>
              <i class='bi bi-circle'></i><span>Modules</span>
            </a>
          </li>
        </ul>
      </li><!-- End  Program Modules Nav -->
      <!-- add student Nav -->
      <li class='nav-item'>
        <a class='nav-link collapsed' data-bs-target='#student-nav' data-bs-toggle='collapse' href='#'>
          <i class='bi bi-journal-text'></i><span>Students</span><i class='bi bi-chevron-down ms-auto'></i>
        </a>
        <ul id='student-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>
          <li>
            <a href='students.php'>
              <i class='bi bi-circle'></i><span>Students</span>
            </a>
          </li>
        </ul>
      </li><!-- End student Nav -->

      <!-- add student Nav -->
      <li class='nav-item'>
        <a class='nav-link collapsed' data-bs-target='#staff-nav' data-bs-toggle='collapse' href='#'>
          <i class='bi bi-journal-text'></i><span>Staff</span><i class='bi bi-chevron-down ms-auto'></i>
        </a>
        <ul id='staff-nav' class='nav-content collapse ' data-bs-parent='#sidebar-nav'>
          <li>
            <a href='staff.php'>
              <i class='bi bi-circle'></i><span>Staff</span>
            </a>
          </li>
        </ul>
      </li>";
    }

    ?>

    </ul>

  </aside>
  <!-- End Sidebar-->