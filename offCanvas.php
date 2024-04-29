<?php
// Get the current script name
$currentScript = $_SERVER['PHP_SELF'];

// Extract the filename from the script path
$currentPage = basename($currentScript);
?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="burgerMenu" aria-labelledby="burgerMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="burgerMenuLabel"><?= 'Hello ' . ucfirst($getUserResult['nameOfUser']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-column gap-2">
            <!-- For Calendar -->
            <?php
                if($currentPage == 'home.php'){
                    ?>
                        <a href="./taskCalendar.php" class="btn btn-primary fs-5 <?= ($currentPage == 'taskCalendar.php') ? 'active' : '' ?>"><i class="bi bi-calendar2-range"></i> Task Calendar</a>
                    <?php
                }else{
                    ?>
                        <a href="./home.php" class="btn btn-primary fs-5 <?= ($currentPage == 'home.php') ? 'active' : '' ?>"><i class="bi bi-house"></i> Home</a>
                    <?php
                }
            ?>
            
            <!-- For Add Staff -->
            <?php if ($getUserResult['positionId'] != 4) : ?>
                <a href="./StaffManagement.php" class="btn btn-primary fs-5 <?= ($currentPage == 'StaffManagement.php') ? 'active' : '' ?>"><i class="bi bi-person-rolodex"></i> Staff Management</a>
            <?php endif; ?>

            <!-- For Section Management -->
            <?php if ($getUserResult['positionId'] == 1) : ?>
                <a href="./SectionManagement.php" class="btn btn-primary fs-5 <?= ($currentPage == 'SectionManagement.php') ? 'active' : '' ?>"><i class="bi bi-building-fill-gear"></i> Section Management</a>
            <?php endif; ?>

            <!-- For Logout -->
            <a href="logout.php" class="btn btn-outline-primary fs-5"><i class="bi bi-power"></i> Logout</a>
        </div>
    </div>
</div>
