<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TimeTable Management System</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <script type="text/javascript" src="assets/js/html2canvas.js" defer></script>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME CSS -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <!-- FLEXSLIDER CSS -->
    <link href="assets/css/flexslider.css" rel="stylesheet" />
    <!-- CUSTOM STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- Google	Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />

    <!--  Jquery Core Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>


    <script src="assets/js/jquery-1.10.2.js" defer></script>
    <!--  Core Bootstrap Script -->
    <script src="assets/js/bootstrap.js" defer></script>
    <!--  Flexslider Scripts -->
    <script src="assets/js/jquery.flexslider.js" defer></script>
    <!--  Scrolling Reveal Script -->
    <script src="assets/js/scrollReveal.js" defer></script>
    <!--  Scroll Scripts -->
    <script src="assets/js/jquery.easing.min.js" defer></script>
    <!--  Custom Scripts -->
    <script src="assets/js/custom.js" defer></script>
    <!-- Recent tags  -->
</head>

<body>
    <br>
    <style>
        table {
            margin-top: 20px;
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 2px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #ffffff;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
    <div id="TT" style="background-color: #FFFFFF">
        <table border="2" cellspacing="3" align="center" id="timetable">
            <caption>
                <strong><br><br>
                    <?php
                    if (isset($_POST['select_semester'])) {
                        echo "COMPUTER ENGINEERING DEPARTMENT SEMESTER " . $_POST['select_semester'] . " ";
                        $year = (int)($_POST['select_semester'] / 2) + $_POST['select_semester'] % 2;
                        $r = mysqli_fetch_assoc(mysqli_query(mysqli_connect("localhost", "root", "", "ttms"), "SELECT * from classrooms
                                WHERE status = '$year'"));
                        echo " ( " . $r['name'], " ) ";
                    }
                    ?>
                </strong>
            </caption>
            <tr>
                <td style="text-align:center">WEEKDAYS</td>
                <td style="text-align:center">8:00-8:50</td>
                <td style="text-align:center">8:55-9:45</td>
                <td style="text-align:center">9:50-10:40</td>
                <td style="text-align:center">10:45-11:35</td>
                <td style="text-align:center">11:40-12:30</td>
                <td style="text-align:center">12:30-1:30</td>
                <td style="text-align:center">1:30-4:00</td>
            </tr>
            <tr>
                <?php
                $table = null;
                if (isset($_POST['select_semester'])) {
                    $table = " semester" . $_POST['select_semester'] . " ";
                } else
                    echo '</table>';
                if (isset($_POST['select_semester']) && $_POST['select_semester'] % 2 !== 0) {
                    $q = mysqli_query(
                        mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM" . $table
                    );
                    $qq = mysqli_query(
                        mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM subjects"
                    );
                    $days = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
                    $i = -1;
                    $str = "<br>";
                    if (isset($_POST['select_semester'])) {
                        while ($r = mysqli_fetch_assoc($qq)) {
                            if ($r['isAlloted'] == 1 && $r['semester'] == $_POST['select_semester']) {
                                $str .= $r['subject_code'] . ": " . $r['subject_name'] . " ";
                                if (isset($r['allotedto'])) {
                                    $id = $r['allotedto'];
                                    $qqq = mysqli_query(
                                        mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM teachers WHERE faculty_number = '$id'"
                                    );
                                    $rr = mysqli_fetch_assoc($qqq);
                                    $str .= " " . $rr['name'] . ": " . $rr['alias'] . " ";
                                }
                                if ($r['course_type'] !== "LAB") {
                                    $str .= "<br>";
                                    continue;
                                } else {
                                    $str .= ", ";
                                }
                                if (isset($r['allotedto2'])) {
                                    $id = $r['allotedto2'];
                                    $qqq = mysqli_query(
                                        mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM teachers WHERE faculty_number = '$id'"
                                    );
                                    $rr = mysqli_fetch_assoc($qqq);
                                    $str .= " " . $rr['name'] . ": " . $rr['alias'] . ", ";
                                }
                                if (isset($r['allotedto3'])) {
                                    $id = $r['allotedto3'];
                                    $qqq = mysqli_query(
                                        mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM teachers WHERE faculty_number = '$id'"
                                    );
                                    $rr = mysqli_fetch_assoc($qqq);
                                    $str .= " " . $rr['alias'] . ": " . $rr['name'] . "<br>";
                                }
                            }
                        }
                    }
                    while ($row = mysqli_fetch_assoc($q)) {
                        $i++;
                        echo "
                 <tr><td style=\"text-align:center\">$days[$i]</td>
                 <td style=\"text-align:center\">{$row['period1']}</td>
                <td style=\"text-align:center\">{$row['period2']}</td>
                <td style=\"text-align:center\">{$row['period3']}</td>
                 <td style=\"text-align:center\">{$row['period4']}</td>
                  <td style=\"text-align:center\">{$row['period5']}</td>
                  <td style=\"text-align:center\">LUNCH</td>
                  <td style=\"text-align:center\">{$row['period6']}</td>
                </tr>\n";
                    }
                    echo '</table>';
                    $sign = "GENERATED VIA TIMETABLE MANAGEMENT SYSTEM, COMPUTER ENGINEERING DEPARTMENT, AMU.";
                    if (isset($_POST['select_semester'])) {
                        echo "<div align=\"center\">" . "<br>" . $str . "<br>
                            <strong>" . $sign . "<br></strong></div>";
                    }
                    unset($_GET['generated']);
                } else {
                    header("location:index.php?generated=false");
                }
                ?>
    </div>


    <div align="center" style="margin-top: 10px">
        <button id="saveaspdf" class="btn btn-info btn-lg" onclick="generatePDF()">SAVE AS PDF</button>
    </div>
    <script type="text/javascript">
        // function generatePDF() {
        //     const {
        //         jsPDF
        //     } = window.jspdf;
        //     const doc = new jsPDF();
        //     doc.text('Hello world!', 100, 100);
        //     doc.save('newFile.pdf');
        // }

        function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const htmlContent = document.getElementById('TT');
            doc.html(htmlContent, {
                callback: function(doc) {
                    doc.save('<?php echo "ttms semester " . $_POST["select_semester"] ?>' + '.pdf');

                }
            });
            alert("Downloaded!");
        }
    </script>

</body>

</html>