<?php
// Start the session
session_start();

if ($_SESSION["accountType"] != "patient") {
    header("Location: main.php");
    exit();
}

// variable for database connection
$servername = "localhost:3308";
$username = "root4";
$password = "";
$dbname = "php_hospital_db";

// try catch block to establish with the database and display error message if occurs
try {
    // connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $ex) {
    echo $ex->getMessage();
}
?>

<?php


if (isset($_POST['makeAppointment'])) {

    try {
        $stmt = $conn->prepare("INSERT INTO appointment_id_info (doctor_id,patient_id)  VALUES (?, ?)");

        $stmt->bindParam(1, $_POST['selectedDoctor'], PDO::PARAM_STR);
        $stmt->bindParam(2, $_SESSION['userId'], PDO::PARAM_STR);
        $stmt->execute();


        $stmt = $conn->query("select * from appointment_id_info order by appointment_id  desc limit 1 ");

        foreach ($stmt as $row) {
            $lastId =  $row['appointment_id'];
        }

        $stmt = $conn->prepare("INSERT INTO appointment_time_info (appointment_id ,start_time,end_time,date)  VALUES (?, ?,?,?)");

        $endTime = explode(":", $_POST['selectedTime']);
        $endTime = $endTime[0] . ":" . "45";
        $stmt->bindParam(1, $lastId, PDO::PARAM_STR);
        $stmt->bindParam(2, $_POST['selectedTime'], PDO::PARAM_STR);
        $stmt->bindParam(3, $endTime, PDO::PARAM_STR);
        $stmt->bindParam(4, $_POST['selectedDate'], PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}

if (isset($_POST['logOut'])) {

    $_SESSION["firstName"] = "";
    $_SESSION["LastName"] = "";
    $_SESSION["userId"] = "";
    $_SESSION["accountType"] = "";

    header("Location: main.php");
    exit();
}

?>

<?php if (isset($_POST['delete'])) {
    try {
        $stmt = $conn->prepare("delete from appointment_time_info where appointment_id  = ? ");

        $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

        $stmt->execute();

        $stmt = $conn->prepare("delete from appointment_id_info where appointment_id  = ? ");

        $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

        $stmt->execute();
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/2dc522e12c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <header class="  d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="main.php" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <i class="fa-solid fa-circle-h"></i>
                <h2> PhP-Hospital </h2>
            </a>
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="main.php" class="nav-link px-2 link-secondary">Home</a></li>
                <li><a href="main.php#aboutus" class="nav-link px-2 link-dark">About Us</a></li>
                <li><a href="main.php#departments" class="nav-link px-2 link-dark">Departments</a></li>
                <li><a href="login.php" class="nav-link px-2 link-dark">Sign In</a></li>
                <li><a href="main.php#doctors" class="nav-link px-2 link-dark">Doctors</a></li>
                <li><a href="weather.html" class="nav-link px-2 link-dark">Weather</a></li>
                <li><a href="main.php#contactus" class="nav-link px-2 link-dark">Contact US</a></li>
            </ul>

            <div class="col-md-3 text-end">
                <a href="login.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Sign In
                </a>
                <a href="sign-up.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Sign
                    Up</a>
            </div>
        </header>

        <?php

        echo "<h1> welcome " . $_SESSION['firstName'] . " " . $_SESSION['LastName'] . "</h1>";


        ?>
        <div class="px-4 pt-5 my-5 text-center ">
            <h1 class="display-4 fw-bold">Make Appointment</h1> <br>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="row gy-3 d-sm-flex justify-content-sm-center">

                <?php if (isset($_POST['selectedDepartment'])) {
                    $departmentId = $_POST['selectedDepartment'];

                ?>
                    <script>
                        window.onload = function() {
                            document.getElementById('selectedDepartment').value = <?php echo  $departmentId; ?>;

                        }
                    </script>

                <?php  }


                ?>
                <div class="col-3 ">
                    <select id="selectedDepartment" name="selectedDepartment" class="form-select" aria-label="Default select example" onchange="this.form.submit()">
                        <option selected>Select Department</option>
                        <?php try {

                            $stmt = $conn->query("select * from departments_info ");
                            foreach ($stmt as $row) {
                        ?>
                                <option value="<?php echo $row['department_id']; ?>"> <?php echo  $row['department_name']; ?></option>
                        <?php }
                        } catch (PDOException $ex) {
                            echo $ex->getMessage();
                        } ?>
                    </select>
                </div>
                <div class="col-3 ">

                    <select name="selectedDoctor" class="form-select" aria-label="Default select example">
                        <option selected>Select Doctor</option>
                        <?php try {
                            $stmt = $conn->query("select * from doctors_info where department_id = " . $departmentId);
                            foreach ($stmt as $row) {
                                //  $doctorId = $row['doctor_id'];
                        ?>
                                <option value="<?php echo $row['doctor_id']; ?>"> <?php echo  $row['first_name']; ?></option>
                        <?php }
                        } catch (PDOException $ex) {
                            echo $ex->getMessage();
                        } ?>
                    </select>
                </div>

                <div class="w-100"></div>
                <div class="col-3 ">
                    <!-- onchange='changeEndTime2(this.value)' -->
                    <select name='selectedTime' class='form-select'>

                        <option> 9:00</option>
                        <option> 10:00</option>
                        <option> 11:00</option>
                        <option> 12:00</option>
                        <option> 13:00</option>
                        <option> 14:00</option>
                        <option> 15:00</option>
                        <option> 16:00</option>
                        <option> 17:00</option>
                    </select>
                </div>
                <div class="col-3 ">

                    <input id="startDate" name="selectedDate" class="form-control" type="date" min="<?php echo date('Y-m-d'); ?>" />
                </div>


                <br> <br>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                    <input type='hidden' name='tableName' value='makeAppointment' />
                    <button type="submit" name="makeAppointment" class="btn btn-primary btn-lg px-4 me-sm-3">Submit</button>

                    <button type="submit" onclick="return confirm('Are you sure you want to log out?')" name="logOut" class="btn btn-outline-danger">Log Out</button>
                </div>
        </div>
        </form>
    </div>

    <div class="container ">

        <h2>my appointments</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Department Name</th>
                        <th scope="col">Doctor Name</th>
                        <th scope="col">start time</th>
                        <th scope="col">end time</th>
                        <th scope="col">date</th>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    try {
                        $stmt = $conn->query(" select * from doctors_info b join appointment_id_info c on b.doctor_id  = c.doctor_id join departments_info d on d.department_id = b.department_id join appointment_time_info e on e.appointment_id = c.appointment_id  where c.patient_id = " . $_SESSION["userId"]);

                        foreach ($stmt as $row) { ?>
                            <tr>
                                <td><?php echo $row['department_name'];  ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name'];  ?></td>
                                <td><?php echo $row['start_time'];  ?></td>
                                <td><?php echo $row['end_time'];  ?></td>
                                <td><?php echo $row['date'];  ?></td>
                                <td> <input type='submit' onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger" name='delete' value="delete" /> </td>
                                <input type='hidden' name='appointmentId' value="<?php echo $row['appointment_id'];  ?>">
                            </tr>
                    <?php }
                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    } ?>
                </tbody>
            </table>
        </form>
    </div>
    <div class="container ">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="main.php" class="nav-link px-2 text-muted">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
                <li class="nav-item"><a href="main.php#aboutus" class="nav-link px-2 text-muted">About</a></li>
                <li class="nav-item"><a href="main.php#contactus" class="nav-link px-2 text-muted">Contact US</a></li>
            </ul>
            <p class="text-center text-muted">© 2021 Company, Inc</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    </div>
</body>

</html>