<?php
require_once('DAL/retrieve.class.php');
require_once('components/header.php');
require_once('components/sidebar.php');
require_once('components/navbar.php');
$dataRetrieval = new UniversityDataRetrieval();

// Fetch data
$avgEarningsSemester = $dataRetrieval->getAverageEarningsPerSemester();
$avgEarningsYear = $dataRetrieval->getAverageEarningsPerYear();
$currentEnrollments = $dataRetrieval->getCurrentSemesterEnrollments();

$enrollmentTrends = $dataRetrieval->getMonthlyEnrollments();

$studentCountByBranch = $dataRetrieval->getStudentCountByBranch();


$recentNewsletters = $dataRetrieval->getRecentNewsletters();
$openFacultyPositions = $dataRetrieval->getOpenFacultyPositions();
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Average Earnings/Semester</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> $<?php echo number_format($avgEarningsSemester[0]['AverageEarningsPerSemester'], 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Average Earnings/Year</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> $<?php echo number_format($avgEarningsYear[0]['AverageEarningsPerYear'], 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Current Semester Enrollments
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo number_format($currentEnrollments[0]['CurrentSemesterEnrollments']); ?></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i> <!-- Graduation cap for students -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Open Faculty Positions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo number_format($openFacultyPositions[0]['OpenFacultyPositions']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-business-time fa-2x text-gray-300"></i> <!-- Business time for open positions -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"> Student Enrollment Trends</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaCharto"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"> Student Count by Branch</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieCharto"></canvas>
                    </div>
                    <!-- <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Direct
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Social
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Referral
                        </span>
                    </div> -->
                </div>
            </div>
        </div>

    </div>
    <!-- Bar Chart -->

    <!-- Bootstrap core JavaScript-->


    <!-- Content Row -->
    <div class="row">
        <!-- Content Column -->
        <?php
        $topRevenueBranches = $dataRetrieval->getTotalRevenueByBranch();
        $totalRevenue = array_sum(array_column($topRevenueBranches, 'TotalRevenue'));
     
        ?>
        <div class="col-lg-5 mb-4 d-flex align-items-stretch">
            <!-- Project Card Example -->
            <div class="card shadow w-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Distribution by Top Branches</h6>
                </div>
                <div class="card-body pb-3">
                    <?php foreach ($topRevenueBranches as $branch) :
                        // Calculate percentage of total revenue
                        $percentage = ($branch['TotalRevenue'] / $totalRevenue) * 100;
                    ?>
                        <h4 class="small font-weight-bold">
                            <?= $branch['BranchName']; ?>
                            <span class="float-right"><?= round($percentage, 2); ?>%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentage; ?>%" aria-valuenow="<?= $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7 d-flex align-items-stretch">
            <div class="card shadow w-100">
                <div class="card-header py-3 text-primary">
                    <strong> NewsLetter</strong>
                    <i class="fa fa-calendar-check text-primary ml-2"></i>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($recentNewsletters as $newsletter): ?>
                            <a class="list-group-item list-group-item-action flex-column align-items-start border border-left-primary">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 text-primary"><?php echo htmlspecialchars($newsletter['Title']); ?></h5>
                                    <small class=" text-primary"><?php echo date('F j, Y', strtotime($newsletter['CreatedAt'])); ?></small>
                                </div>
                                <p class="mb-1"><?php echo htmlspecialchars($newsletter['Content']); ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->


<?php require_once("components/footer.php"); ?>



<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>



<?php require_once("components/scripts.php"); ?>
<script>
    // Convert PHP data to JavaScript array
    const enrollmentTrends = <?php echo json_encode($enrollmentTrends); ?>;

    // Prepare the data for Chart.js
    const years = enrollmentTrends.map(item => item.Year);
    const enrollmentCounts = enrollmentTrends.map(item => parseInt(item.EnrollmentCount));
    const cumulativeEnrollments = enrollmentTrends.map(item => parseInt(item.CumulativeEnrollment));

    // Create the chart
    const vtx = document.getElementById("myAreaCharto").getContext('2d');
    const myAreaChart = new Chart(vtx, {
        type: 'line', // Type 'line' for area chart with a filled area
        data: {
            labels: years, // X-axis labels
            datasets: [{
                label: "Enrollment Count",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                hoverRadius: 0, // Disabling hover effects
                hoverBackgroundColor: 'transparent',
                hoverBorderColor: 'transparent',
                data: enrollmentCounts, // Y-axis data for enrollment count
            }, {
                label: "Cumulative Enrollment",
                lineTension: 0.3,
                backgroundColor: "rgba(28, 200, 138, 0.05)",
                borderColor: "rgba(28, 200, 138, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(28, 200, 138, 1)",
                pointBorderColor: "rgba(28, 200, 138, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                hoverRadius: 0, // Disabling hover effects
                hoverBackgroundColor: 'transparent',
                hoverBorderColor: 'transparent',
                data: cumulativeEnrollments, // Y-axis data for cumulative enrollment
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    time: {
                        unit: 'year'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            legend: {
                display: true // Show the legend
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: 'blue',
                bodyColor: "#858796",
                titleColor: '#6e707e',
                borderColor: 'blue',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,

                mode: 'index',
                intersect: false,
                caretPadding: 10
            }
        }
    });

    // Example for the pie chart
    var ctx = document.getElementById("myPieCharto");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_column($studentCountByBranch, 'BranchName')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($studentCountByBranch, 'StudentCount')); ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                position: 'top'
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
        }
    });
</script>


</body>

</html>