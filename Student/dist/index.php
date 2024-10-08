<?php require_once('components/header.php') ?>
<?php require_once('components/navbar.php') ?>
<?php require_once('DAL/retrieve.class.php');

// Create an instance of the UniversityDataRetrieval class
$universityData = new UniversityDataRetrieval();
$departmentID = isset($_SESSION['departmentID']) ? $_SESSION['departmentID'] : 9;
// echo var_dump($departmentID);
$userID = isset($_GET['student_id']) ? intval($_GET['student_id']) : (isset($_SESSION['userID']) ? $_SESSION['userID'] : 3);
// echo var_dump($userID);
// Fetch the total credits and fees for the student based on the selected semester and year
$gpa = $universityData->getStudentGPA($userID);
$grades = $universityData->getGradesCount($userID);
$jsonGrades = json_encode($grades[0]);
$credits = $universityData->getCreditsCount($userID);
$jsonCredits = json_encode($credits[0]);

$semester_gpa = $universityData->getStudentGPAPerSemester($userID);
$cumulative_semester_gpa = $universityData->getStudentCumulativeGPAPerSemester($userID);


?>

<style>
  #card-body {
    display: flex;
    justify-content: center;
    /* Horizontally center the content */
    align-items: center;
    /* Vertically center the content */
    width: 100%;
    height: 19rem;

  }

  #gpa-text {
    text-align: center;
    /* Center the text */
    font-weight: bold;
    /* Make the text bold */
    font-size: 16px;
    /* Adjust font size */
    margin-top: 10px;
    /* Space above the GPA text */
  }

  .chart-container {
    margin-bottom: 30px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    background-color: #fff;
  }

  #grades-pie {
    width: 100%;
  }
</style>

<!-- Include jqPlot CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.min.css">
<div class="my-3 my-md-5">
  <div class="container">

    <div class="row row-cards">

      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Grade Point Average for the current semester </h3>
          </div>
          <div class="card-body" id="card-body" style="width:100%; ">

          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="alert alert-primary">Your Academic Profile? See Below!</a> </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Credits Status</h3>


              </div>
              <div class="card-body">
                <div id="chart-donut" style="height: 12rem;"></div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Course Grades Distribution</h3>
                <br>

              </div>
              <div class="card-body">
                <div id="chart-pie" style="height: 12rem;"></div>
              </div>

            </div>
          </div>


        </div>
      </div>


    </div>
    <div class="chart-container">
      <div id="chart-line" style="height: 300px;"></div>
    </div>
  </div>


</div>


<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!-- Include jqPlot JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.min.js"></script>

<!-- Include MeterGaugeRenderer Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/plugins/jqplot.meterGaugeRenderer.min.js"></script>

<script>
  $(document).ready(function() {
    // Example GPA value
    var gpaValue = <?php echo json_encode($gpa) ?>;

    // GPA Meter with range from 0 to 4, with 0.5 steps
    var s1 = [gpaValue];

    var plot3 = $.jqplot('card-body', [s1], {
      seriesDefaults: {
        renderer: $.jqplot.MeterGaugeRenderer,
        rendererOptions: {
          min: 0,
          max: 4, // Maximum GPA
          intervals: [0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4], // 0.5 step intervals
          intervalColors: [
            '#ff0000', '#ff3333', '#ff6666', '#ff9999', // Red shades
            '#b3ffb3', '#80ff80', '#4dff4d', '#00ff00' // Brighter green shades
          ],
          ticks: [0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4], // Tick marks
          label: 'GPA: <?php echo json_encode(number_format($gpa, 2)); ?>', // Label under the meter
          labelHeightAdjust: 135,
          padding: 35,
          style: {
            border: 0
          },
          gaugeStyle: 'half', // Render half-circle gauge
        }
      }
    });
  });

  require(['c3', 'jquery'], function(c3, $) {
    $(document).ready(function() {
      var chart = c3.generate({
        bindto: '', // id of chart wrapper
        data: {
          columns: [
            // each columns data
            ['data1', 11, 8, 15, 18, 19, 17]

          ],
          type: 'area', // default type of chart
          colors: {
            'data1': tabler.colors["blue"],

          },
          names: {
            // name of each serie
            'data1': 'GPA',

          }
        },
        axis: {
          x: {
            type: 'category',
            // name of each category
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
          },
        },
        legend: {
          show: false, //hide legend
        },
        padding: {
          bottom: 0,
          top: 0
        },
      });
    });
  });
</script>
<script>
  require(['c3', 'jquery'], function(c3, $) {
    $(document).ready(function() {
      // Assuming PHP passes the encoded JSON to this script
      var credits = <?php echo $jsonCredits; ?>;

      // Generate the C3.js donut chart
      var chart = c3.generate({
        bindto: '#chart-donut', // id of chart wrapper
        data: {
          columns: [
            // Use the string keys to access completed and remaining credits
            ['Credits Completed', credits['completed_credits']],
            ['Credits Remaining to Graduate', credits['remaining_credits']]
          ],
          type: 'donut', // default type of chart
          colors: {
            'Credits Completed': tabler.colors["green-light"],
            'Credits Remaining to Graduate': tabler.colors["green"]
          },
          names: {
            // Name of each series (already provided in the columns array)
            'Credits Completed': 'Credits Completed',
            'Credits Remaining to Graduate': 'Credits Remaining to Graduate'
          }
        },
        axis: {},
        legend: {
          show: false, // Hide legend
        },
        padding: {
          bottom: 0,
          top: 0
        }
      });
    });
  });
</script>



<script>
  require(['c3', 'jquery'], function(c3, $) {
    $(document).ready(function() {
      // Assuming PHP passes the encoded JSON to this script
      var grades = <?php echo $jsonGrades; ?>;

      // Convert the grades array into columns for C3.js
      var columnsData = [
        ['A+', parseInt(grades['A_Plus'])],
        ['A', parseInt(grades['A'])],
        ['B+', parseInt(grades['B_Plus'])],
        ['B', parseInt(grades['B'])],
        ['C+', parseInt(grades['C_Plus'])],
        ['C', parseInt(grades['C'])],
        ['D+', parseInt(grades['D_Plus'])],
        ['D', parseInt(grades['D'])],
        ['F', parseInt(grades['F'])]
      ];

      // Generate the C3.js pie chart
      var chart = c3.generate({
        bindto: '#chart-pie', // id of chart wrapper
        data: {
          columns: columnsData, // Dynamic columns data
          type: 'pie', // default type of chart
          colors: {
            'A+': tabler.colors["blue-darker"],
            'A': tabler.colors["blue"],
            'B+': tabler.colors["green-darker"],
            'B': tabler.colors["green"],
            'C+': tabler.colors["yellow-darker"],
            'C': tabler.colors["yellow"],
            'D+': tabler.colors["orange-darker"],
            'D': tabler.colors["orange"],
            'F': tabler.colors["red"]
          },
          names: {
            'A+': 'A+',
            'A': 'A',
            'B+': 'B+',
            'B': 'B',
            'C+': 'C+',
            'C': 'C',
            'D+': 'D+',
            'D': 'D',
            'F': 'F'
          }
        },
        axis: {},
        legend: {
          show: false, //hide legend
        },
        padding: {
          bottom: 0,
          top: 0
        },
      });
    });
  });
</script>

<?php

// Encode the PHP array to JSON format
$jsonCumulativeSemesterGPA = json_encode($cumulative_semester_gpa);
$jsonSemesterGPA = json_encode($semester_gpa);
?>
<script>
  require(['c3', 'jquery'], function(c3, $) {
    $(document).ready(function() {
      // Assuming PHP passes the encoded JSON to this script
      var semesterGPA = <?php echo $jsonSemesterGPA; ?>;
      var cumulativeGPA = <?php echo $jsonCumulativeSemesterGPA; ?>;

      console.log('Semester GPA Data:', semesterGPA); // Debug: Check semester GPA data
      console.log('Cumulative GPA Data:', cumulativeGPA); // Debug: Check cumulative GPA data

      // Convert the semester GPA data into the format required by C3.js
      var semesterColumnsData = ['Semester GPA'].concat(semesterGPA.map(entry => parseFloat(entry.GPA)));

      // Convert the cumulative GPA data into the format required by C3.js
      var cumulativeColumnsData = ['Cumulative GPA'].concat(cumulativeGPA.map(entry => parseFloat(entry.CumulativeGPA)));

      // Prepare categories for both GPA data (they should be the same for both GPA and cumulative GPA)
      var categories = semesterGPA.map(entry => `${entry.SemesterTaken}-${entry.YearTaken}`);

      console.log('Categories:', categories); // Debug: Check categories

      // Generate the C3.js line chart with both GPA and Cumulative GPA curves
      var chart = c3.generate({
        bindto: '#chart-line', // ID of the chart wrapper
        data: {
          columns: [
            semesterColumnsData, // Data for the semester GPA chart
            cumulativeColumnsData // Data for the cumulative GPA chart
          ],
          type: 'line', // Chart type
          colors: {
            'GPA': '#003366', // Color for the semester GPA line
            'Cumulative GPA': '#ff5733' // Color for the cumulative GPA line
          },
          line: {
            width: 3 // Increase line width for better visibility
          }
        },
        axis: {
          x: {
            type: 'category',
            label: 'Semester/Year', // Label for the X-axis
            tick: {
              rotate: 45, // Rotate labels for better readability
              multiline: false,
              fit: true // Ensure labels fit within the x-axis area
            },
            categories: categories // Set the categories for the X-axis
          },
          y: {
            label: 'GPA', // Label for the Y-axis
            tick: {
              format: function(d) {
                return d.toFixed(2); // Format tick labels to 1 decimal
              },
              values: [0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4] // Explicitly specify y-axis values
            },
            min: 0,
            max: 4,
            padding: {
              top: 40,
              bottom: 10
            }
          }
        },
        tooltip: {
          show: true, // Enable tooltips
          format: {
            title: function(d) {
              return 'Semester/Year: ' + categories[d]; // Show semester/year in tooltip
            },
            value: function(value, ratio, id) {
              return id + ': ' + value.toFixed(2); // Show GPA value in tooltip
            }
          }
        },
        legend: {
          show: true, // Show the legend
          position: 'right', // Position of the legend
          item: {
            onclick: function(id) {
              return false; // Disable legend item click
            },
            onmouseover: function(id) {
              chart.focus(id);
            },
            onmouseout: function(id) {
              chart.revert();
            }
          }
        },
        point: {
          r: 6, // Size of points
          focus: {
            expand: {
              r: 8 // Size of focused points
            }
          }
        },
        padding: {
          bottom: 10, // Adjusted padding for better spacing
          top: 20
        },
        grid: {
          y: {
            show: true // Show horizontal grid lines
          }
        },
        transition: {
          duration: 1000 // Smooth transition duration
        }
      });
    });
  });
</script>






<?php require_once("components/footer.php") ?>


</body>

</html>