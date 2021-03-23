<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="nav navbar-nav ml-auto">

        <?php

        if (can_access_finance()) {
            echo "
            <li class='nav-item'>
                <a class='nav-link' href='finance.php' id='navStudentFees'>Fees</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='defaulters.php' id='navDefaulters'>Balance</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='paid.php' id='navPaid'>Paid</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='payment_mode.php' id='navPaymentMode'>Payment Mode</a>
            </li>
        ";
        }
        ?>


        <div class="dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" id="navArchived">ARCHIVED<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php
                if (can_access_finance()) {
                    echo "<li class='nav-item'><a class='nav-link' href='archived.php' id='navArchivedStudents'>FEES</a></li>";
                }
                ?>
                <li class="nav-item"><a class="nav-link" href="students_tc.php" id="navStudentsTC">Students TC</a></li>
            </ul>
        </div>

        <li class="nav-item">
            <a class="btn btn-sm btn-danger" href="logout.php">Logout</a>
        </li>
    </ul>
</div>
</div>
</nav>