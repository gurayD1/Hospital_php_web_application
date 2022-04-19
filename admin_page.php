<?php
session_start();

if ($_SESSION["accountType"] != "admin") {
    header("Location: main.php");
    exit();
}

if (isset($_POST['logOut'])) {

    $_SESSION["firstName"] = "";
    $_SESSION["LastName"] = "";
    $_SESSION["userId"] = "";
    $_SESSION["accountType"] = "";

    header("Location: main.php");
    exit();
}

$servername = "localhost:3308";
$username = "root4";
$password = "";
$dbname = "php_hospital_db";
$emailExist = false;

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

if (isset($_POST['delete'])) {

    if ($_POST['tableName'] == 'patient_info') {
        try {
            $stmt = $conn->prepare("delete from patient_info where patient_id = ? ");

            $stmt->bindParam(1, $_POST['patientId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'doctors_info') {

        try {
            $stmt = $conn->prepare("delete from doctors_info where doctor_id = ? ");

            $stmt->bindParam(1, $_POST['doctorId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_id_info') {

        try {
            $stmt = $conn->prepare("delete from appointment_id_info where appointment_id = ? ");

            $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $conn->prepare("delete from appointment_time_info where appointment_id = ? ");

            $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_time_info') {
        try {
            $stmt = $conn->prepare("delete from appointment_time_info where appointment_id = ? ");

            $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $conn->prepare("delete from appointment_id_info where appointment_id = ? ");

            $stmt->bindParam(1, $_POST['appointmentId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'contact_us') {
        try {
            $stmt = $conn->prepare("delete from contact_us where message_id  = ? ");

            $stmt->bindParam(1, $_POST['messageId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'departments_info') {

        try {
            $stmt = $conn->prepare("delete from departments_info where department_id  = ? ");

            $stmt->bindParam(1, $_POST['departmentId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
}

?>

<?php

if (isset($_POST['update'])) {

    if ($_POST['tableName'] == 'patient_info') {
        try {
            $stmt = $conn->prepare("update  patient_info set first_name = ?, last_name = ?, email = ?, password = ?, address = ?, phone_number = ?  where patient_id = ? ");

            $stmt->bindParam(1, $_POST['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['last_name'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['password'], PDO::PARAM_STR);
            $stmt->bindParam(5, $_POST['address'], PDO::PARAM_STR);
            $stmt->bindParam(6, $_POST['phone_number'], PDO::PARAM_STR);
            // $stmt->bindParam(7, $_POST['sign_up_date'], PDO::PARAM_STR);
            $stmt->bindParam(7, $_POST['patientId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'doctors_info') {

        try {
            $stmt = $conn->prepare("update  doctors_info set first_name = ?, last_name = ?, department_id = ?, email = ?, password = ?, address = ?, phone_number = ?  where doctor_id = ? ");

            $stmt->bindParam(1, $_POST['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['last_name'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['department_id'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(5, $_POST['password'], PDO::PARAM_STR);
            $stmt->bindParam(6, $_POST['address'], PDO::PARAM_STR);
            $stmt->bindParam(7, $_POST['phone_number'], PDO::PARAM_STR);
            $stmt->bindParam(8, $_POST['doctorId'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_id_info') {

        try {
            $stmt = $conn->prepare("update appointment_id_info set doctor_id = ?, patient_id = ?  where appointment_id = ? ");
            $stmt->bindParam(1, $_POST['doctor_id'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['patient_id'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['appointmentId'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_time_info') {
        try {
            $stmt = $conn->prepare("update appointment_time_info set start_time = ?, end_time = ?, date = ?  where appointment_id = ? ");
            $endTime = explode(":", $_POST['start_time']);
            echo $endTime[0];
            $newEndTime = $endTime[0] . ":" . "45";
            $stmt->bindParam(1, $_POST['start_time'], PDO::PARAM_STR);
            $stmt->bindParam(2, $newEndTime, PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['date'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['appointmentId'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'departments_info') {

        try {
            $stmt = $conn->prepare("update departments_info set department_name = ?  where department_id = ? ");
            $stmt->bindParam(1, $_POST['department_name'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['departmentId'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
}

?>

<?php

if (isset($_POST['addRow'])) {
    if ($_POST['tableName'] == 'patient_info') {

        try {
            $stmt = $conn->prepare("INSERT INTO patient_info (first_name,last_name,email,password,address,phone_number)  VALUES ( ?, ?, ?, ?, ?,?)");
            $stmt->bindParam(1, $_POST['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['last_name'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['password'], PDO::PARAM_STR);
            $stmt->bindParam(5, $_POST['address'], PDO::PARAM_STR);
            $stmt->bindParam(6, $_POST['phone_number'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'doctors_info') {

        try {
            $stmt = $conn->prepare("INSERT INTO doctors_info (first_name,last_name,department_id,email,password,address,phone_number)  VALUES (?, ?, ?, ?, ?, ?,?)");
            $stmt->bindParam(1, $_POST['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['last_name'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['department_id'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(5, $_POST['password'], PDO::PARAM_STR);
            $stmt->bindParam(6, $_POST['address'], PDO::PARAM_STR);
            $stmt->bindParam(7, $_POST['phone_number'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_id_info') {

        try {
            $stmt = $conn->prepare("INSERT INTO appointment_id_info (doctor_id,patient_id)  VALUES (?, ?)");
            $stmt->bindParam(1, $_POST['doctor_id'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['patient_id'], PDO::PARAM_STR);
            $stmt->execute();
            $stmt = $conn->query("select * from appointment_id_info order by appointment_id  desc limit 1 ");
            foreach ($stmt as $row) {
                $lastId =  $row['appointment_id'];
            }

            $stmt = $conn->prepare("INSERT INTO appointment_time_info (appointment_id,start_time,end_time,date)  VALUES (?, ?,?,?)");

            $sampleStartTime = "9:00";
            $sampleEndTime = "9:45";
            $sampleDate = date("Y/m/d");
            $stmt->bindParam(1, $lastId, PDO::PARAM_STR);
            $stmt->bindParam(2, $sampleStartTime, PDO::PARAM_STR);
            $stmt->bindParam(3, $sampleEndTime, PDO::PARAM_STR);
            $stmt->bindParam(4, $sampleDate, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'appointment_time_info') {

        try {

            $stmt = $conn->prepare("INSERT INTO appointment_time_info (start_time,end_time,date)  VALUES (?, ?,?)");
            $stmt->bindParam(1, $_POST['start_time'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['endTime'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['date'], PDO::PARAM_STR);
            $stmt->execute();
            $stmt = $conn->query("select * from appointment_time_info order by appointment_id  desc limit 1 ");
            foreach ($stmt as $row) {
                $lastId =  $row['appointment_id'];
            }

            $stmt = $conn->prepare("INSERT INTO appointment_id_info (appointment_id,doctor_id,patient_id)  VALUES (?, ?,?)");
            $sampleDoctorId = 1;
            $samplePatientId = 1;
            $stmt->bindParam(1, $lastId, PDO::PARAM_STR);
            $stmt->bindParam(2, $sampleDoctorId, PDO::PARAM_STR);
            $stmt->bindParam(3, $samplePatientId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else if ($_POST['tableName'] == 'departments_info') {
        try {
            $stmt = $conn->prepare("INSERT INTO departments_info (department_name)  VALUES (?)");

            $stmt->bindParam(1, $_POST['department_name'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>

    <div class="container-xxl">
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
                <li><a href="weather.php" class="nav-link px-2 link-dark">Weather</a></li>
                <li><a href="main.php#contactus" class="nav-link px-2 link-dark">Contact US</a></li>
            </ul>

            <div class="col-md-3 text-end">
                <a href="login.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Sign In
                </a>
                <a href="sign-up.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Sign
                    Up</a>
            </div>
        </header>
    </div>

    <div class="container">
        <h1>Admin Panel</h1>
        <br>
      
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <select class="form-select" id="selectionMenu" name="adminChoice">
                <option selected value="0">Open this select menu</option>
                <option value="1">All patients information</option>
                <option value="2">All doctors information</option>
                <option value="3">All appointments information</option>
                <option value="4">All users message</option>
                <option value="5">All department information</option>
                <option value="6">Display All</option>
            </select>
            <br>
            <input type='submit' class="btn btn-success" name='submit' value='submit' />
            <button type="submit" name="logOut" onclick="return confirm('Are you sure you want to log out?')" class="btn btn-outline-danger">Log Out</button>
        </form>
        <br>

        <?php
        if (isset($_POST['adminChoice'])) {

            $_SESSION["adminChoice"] = $_POST['adminChoice'];
        }

        if ($_SESSION["adminChoice"] == 1) {
        ?>
            <script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 1;
                    document.getElementById('patientInfoSection').style.display = "block";
                    document.getElementById('doctorInfoSection').style.display = "none";
                    document.getElementById('appointmentInfoSection').style.display = "none";
                    document.getElementById('contactInfoSection').style.display = "none";
                    document.getElementById('departmentInfoSection').style.display = "none";
                }
            </script>
        <?php
        } else if ($_SESSION["adminChoice"] == 2) {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 2;
                    document.getElementById('patientInfoSection').style.display = "none";
                    document.getElementById('doctorInfoSection').style.display = "block";
                    document.getElementById('appointmentInfoSection').style.display = "none";
                    document.getElementById('contactInfoSection').style.display = "none";
                    document.getElementById('departmentInfoSection').style.display = "none";
                }
            </script>
        <?php
        } else if ($_SESSION["adminChoice"] == 3) {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 3;
                    document.getElementById('patientInfoSection').style.display = "none";
                    document.getElementById('doctorInfoSection').style.display = "none";
                    document.getElementById('appointmentInfoSection').style.display = "block";
                    document.getElementById('contactInfoSection').style.display = "none";
                    document.getElementById('departmentInfoSection').style.display = "none";
                }
            </script>
        <?php
        } else if ($_SESSION["adminChoice"] == 4) {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 4;
                    document.getElementById('patientInfoSection').style.display = "none";
                    document.getElementById('doctorInfoSection').style.display = "none";
                    document.getElementById('appointmentInfoSection').style.display = "none";
                    document.getElementById('contactInfoSection').style.display = "block";
                    document.getElementById('departmentInfoSection').style.display = "none";
                }
            </script>
        <?php
        } else if ($_SESSION["adminChoice"] == 5) {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 5;
                    document.getElementById('patientInfoSection').style.display = "none";
                    document.getElementById('doctorInfoSection').style.display = "none";
                    document.getElementById('appointmentInfoSection').style.display = "none";
                    document.getElementById('contactInfoSection').style.display = "none";
                    document.getElementById('departmentInfoSection').style.display = "block";
                }
            </script>
        <?php
        } else if ($_SESSION["adminChoice"] == 6) {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 6;
                    document.getElementById('patientInfoSection').style.display = "block";
                    document.getElementById('doctorInfoSection').style.display = "block";
                    document.getElementById('appointmentInfoSection').style.display = "block";
                    document.getElementById('contactInfoSection').style.display = "block";
                    document.getElementById('departmentInfoSection').style.display = "block";
                }
            </script>
        <?php
        } else {
        ?><script>
                window.onload = function() {
                    document.getElementById('selectionMenu').value = 0;
                    document.getElementById('patientInfoSection').style.display = "none";
                    document.getElementById('doctorInfoSection').style.display = "none";
                    document.getElementById('appointmentInfoSection').style.display = "none";
                    document.getElementById('contactInfoSection').style.display = "none";
                    document.getElementById('departmentInfoSection').style.display = "none";
                }
            </script>
        <?php
        }


        ?>
        <section id="patientInfoSection">
            <h3> All patients information </h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">patient id</th>
                        <th scope="col">first name</th>
                        <th scope="col">last name</th>
                        <th scope="col">email</th>
                        <th scope="col">password</th>
                        <th scope="col">address</th>
                        <th scope="col">phone number</th>
                        <th scope="col">sign up date</th>
                        <th colspan="2"> Action </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conn->query("select * from patient_info");
                        foreach ($stmt as $row) {
                            echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' >";
                            echo "<tr>";
                            echo "<td>" . $row['patient_id'] . "</td>";
                            echo "<td><textarea name='first_name'>" . $row['first_name'] . "</textarea></td>";
                            echo "<td><textarea name='last_name'>" . $row['last_name'] . "</textarea></td>";
                            echo "<td><textarea name='email'>" . $row['email'] . "</textarea></td>";
                            echo "<td><textarea name='password'>" . $row['password'] . "</textarea></td>";
                            echo "<td><textarea name='address'>" . $row['address'] . "</textarea></td>";
                            echo "<td><textarea name='phone_number'>" . $row['phone_number'] . "</textarea></td>";
                            echo "<td>" . $row['sign_up_date'] . "</td>";
                            echo "<td> <input type='hidden' name='patientId' value='" . $row['patient_id'] . "'> <input type='submit' onclick=\"return confirm('Are you sure you want to update?')\" class='btn btn-warning' name='update' value='update'/></td>";
                            echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\" class='btn btn-danger' name='delete' value='delete'/> </td> ";
                            echo " <input type='hidden' name='tableName' value='patient_info'/> "; //*** */
                            echo "</tr>";
                            echo "</form>";
                        }
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>add new record</td>";
                        echo "<td><textarea name='first_name' placeholder='first name'></textarea></td>";
                        echo "<td><textarea name='last_name' placeholder='last name'></textarea></td>";
                        echo "<td><textarea name='email' placeholder='email'></textarea></td>";
                        echo "<td><textarea name='password' placeholder='password'></textarea></td>";
                        echo "<td><textarea name='address' placeholder='address'></textarea></td>";
                        echo "<td><textarea name='phone_number' placeholder='phone number'></textarea></td>";
                        echo "<td></td>";

                        echo "<td colspan='2'><input  type='submit' class='btn btn-info' name='addRow' value='add row'/> </td>";
                        echo " <input type='hidden' name='tableName' value='patient_info'/> "; //**** */
                        echo "</tr>";
                        echo "</form>";
                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    } ?>
                </tbody>
            </table>
        </section>


        <section id="doctorInfoSection">
            <?php
            try {
            
                $stmt = $conn->query("select * from doctors_info");
            ?>

                <h3> All doctors information </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">doctor id</th>
                            <th scope="col">first name</th>
                            <th scope="col">last name</th>
                            <th scope="col">department id</th>
                            <th scope="col">email</th>
                            <th scope="col">password</th>
                            <th scope="col">address</th>
                            <th scope="col">phone number</th>
                            <th scope="col">sign up date</th>
                            <th colspan="2"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($stmt as $row) {


                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>" . $row['doctor_id'] . "</td>";
                        echo "<td><textarea name='first_name'>" . $row['first_name'] . "</textarea></td>";
                        echo "<td><textarea name='last_name'>" . $row['last_name'] . "</textarea></td>";
                        echo "<td><textarea name='department_id'>" . $row['department_id'] . "</textarea></td>";
                        echo "<td><textarea name='email'>" . $row['email'] . "</textarea></td>";
                        echo "<td><textarea name='password'>" . $row['password'] . "</textarea></td>";
                        echo "<td><textarea name='address'>" . $row['address'] . "</textarea></td>";
                        echo "<td><textarea name='phone_number'>" . $row['phone_number'] . "</textarea></td>";
                        echo "<td>" . $row['sign_up_date'] . "</td>";
                        echo " <input type='hidden' name='tableName' value='doctors_info'/> "; //*** */
                        echo "<td> <input type='hidden' name='doctorId' value='" . $row['doctor_id'] . "'> <input type='submit' onclick=\"return confirm('Are you sure you want to update?')\"   class='btn btn-warning' name='update' value='update'/></td>";
                        echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\"  class='btn btn-danger' name='delete' value='delete'/> </td> ";
                        echo "</tr>";
                        echo "</form>";
                    }
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                    echo "<tr>";
                    echo "<td>add new record</td>";
                    echo "<td><textarea name='first_name' placeholder='first name'></textarea></td>";
                    echo "<td><textarea name='last_name' placeholder='last name'></textarea></td>";
                    echo "<td><textarea name='department_id' placeholder='department_id'></textarea></td>";
                    echo "<td><textarea name='email' placeholder='email'></textarea></td>";
                    echo "<td><textarea name='password' placeholder='password'></textarea></td>";
                    echo "<td><textarea name='address' placeholder='address'></textarea></td>";
                    echo "<td><textarea name='phone_number' placeholder='phone number'></textarea></td>";
                    echo "<td></td>";
                    echo "<td colspan='2'><input type='submit' name='addRow' class='btn btn-info' value='add row'/> </td>";
                    echo " <input type='hidden' name='tableName' value='doctors_info'/> "; //*** */
                    echo "</tr>";
                    echo "</form>";

                } catch (PDOException $ex) {
                    echo $ex->getMessage();
                } ?>
                    </tbody>
                </table>
        </section>
        <section id="appointmentInfoSection">

            <?php
            try {

                $stmt = $conn->query("select * from appointment_id_info");
            ?>

                <h3> All appointments information </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">appointment id</th>
                            <th scope="col">doctor id</th>
                            <th scope="col">patient id</th>
                            <th scope="col" colspan="2"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($stmt as $row) {


                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>" . $row['appointment_id'] . "</td>";
                        echo "<td><textarea name='doctor_id'>" . $row['doctor_id'] . "</textarea></td>";
                        echo "<td><textarea name='patient_id'>" . $row['patient_id'] . "</textarea></td>";
                        echo " <input type='hidden' name='tableName' value='appointment_id_info'/> "; 
                        echo "<td> <input type='hidden' name='appointmentId' value='" . $row['appointment_id'] . "'> <input type='submit' onclick=\"return confirm('Are you sure you want to update?')\"   class='btn btn-warning' name='update' value='update'/></td>";
                        echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\" class='btn btn-danger' name='delete' value='delete'/> </td> ";

                        echo "</tr>";
                        echo "</form>";
                    }
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                    echo "<tr>";
                    echo "<td>add new record</td>";
                    echo "<td><textarea name='doctor_id' placeholder='doctor id'></textarea></td>";
                    echo "<td><textarea name='patient_id' placeholder='patient id'></textarea></td>";
                    echo "<td colspan='2'><input type='submit' name='addRow' class='btn btn-info' value='add row'/> </td>";
                    echo "<input type='hidden' name='tableName' value='appointment_id_info'/>"; 
                    echo "</tr>";
                    echo "</form>";

                } catch (PDOException $ex) {
                    echo $ex->getMessage();
                } ?>
                    </tbody>
                </table>


                <?php
                try {
                   
                    $stmt = $conn->query("select * from appointment_time_info");
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">appointment id</th>
                                <th scope="col">start_time</th>
                                <th scope="col">end_time</th>
                                <th scope="col">date</th>
                                <th scope="col" colspan="2"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $myIndex = 0;
                        foreach ($stmt as $row) {
                            echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                            echo "<tr>";
                            echo "<td>" . $row['appointment_id'] . "</td>";
                            echo "<td> <select name='start_time' class='form-select' >
                            <option selected disabled>" . $row['start_time'] . " </option>
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
                            </td>";
                            echo "<td id='endTime' name='end_time'>" . $row['end_time'] . " </td>";
                            echo "<td><input name='date' id='datePickerId' type='date' required value='" . $row['date'] . "' min='" . date('Y-m-d') . "' > </textarea></td>";
                            echo " <input type='hidden' name='tableName' value='appointment_time_info'/> "; 
                            echo "<td> <input type='hidden' name='appointmentId' value='" . $row['appointment_id'] . "'> <input type='submit' onclick=\"return confirm('Are you sure you want to update?')\"  class='btn btn-warning' name='update' value='update'/></td>";
                            echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\"  class='btn btn-danger' name='delete' value='delete'/> </td> ";
                            echo "</tr>";
                            echo "</form>";
                        }
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>add new record</td>";
                        echo "<td> <select name='start_time' class='form-select' onchange='changeEndTime2(this.value)'>
                           
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
                            </td>";
                        echo "<td id='endTime2' name='end_time'> </td>";
                        echo "<td><input name='date' id='datePickerId' type='date' required min='" . date('Y-m-d') . "'> </textarea></td>";
                        echo " <input type='hidden' name='tableName' value='appointment_time_info'/> "; 
                        echo " <input type='hidden' name='endTime' id='inputEndTime2'/> "; 
                        echo "<td colspan='2'><input type='submit' name='addRow' class='btn btn-info' value='add row'/> </td>";
                        echo "</tr>";
                        echo "</form>";
                        echo "<script>function changeEndTime2(value){ var value1 = parseInt(value.split(':')); document.getElementById('endTime2').innerHTML = value1 + ':' + 45;
                            document.getElementById('inputEndTime2').value = value1 + ':' + 45; }  </script>";

                    } catch (PDOException $ex) {
                        echo $ex->getMessage();
                    } ?>
                        </tbody>
                    </table>
        </section>
        <section id="contactInfoSection">
            <?php
            try {
                $stmt = $conn->query("select * from contact_us");
            ?>
                <h3> All users message </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">message id</th>
                            <th scope="col">first name</th>
                            <th scope="col">last name</th>
                            <th scope="col">email</th>
                            <th scope="col">user message</th>
                            <th scope="col">sent date</th>
                            <th scope="col"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($stmt as $row) {
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>" . $row['message_id'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email_address'] . "</td>";
                        echo "<td>" . $row['user_message'] . "</td>";
                        echo "<td>" . $row['date_time'] . "</td>";
                        echo " <input type='hidden' name='tableName' value='contact_us'/>";
                        echo " <input type='hidden' name='messageId' value='" . $row['message_id'] . "'> ";
                        echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\"  class='btn btn-danger' name='delete' value='delete'/> </td> ";
                        echo "</tr>";
                        echo "</form>";
                    }

                } catch (PDOException $ex) {
                    echo $ex->getMessage();
                } ?>
                    </tbody>
                </table>
                
        </section>


        <section id="departmentInfoSection">
            <?php
            try {
                $stmt = $conn->query("select * from departments_info");
            ?>

                <h3> All department information </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">department id</th>
                            <th scope="col">department name</th>
                            <th colspan="2"> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($stmt as $row) {


                        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                        echo "<tr>";
                        echo "<td>" . $row['department_id'] . "</td>";
                        echo "<td><textarea name='department name'>" . $row['department_name'] . "</textarea></td>";
                        echo " <input type='hidden' name='tableName' value='departments_info'/> "; //*** */
                        echo "<td> <input type='hidden' name='departmentId' value='" . $row['department_id'] . "'> <input type='submit'  onclick=\"return confirm('Are you sure you want to update?')\"  class='btn btn-warning' name='update' value='update'/></td>";
                        echo "<td><input type='submit' onclick=\"return confirm('Are you sure you want to delete?')\"  class='btn btn-danger' name='delete' value='delete'/> </td> ";
                        echo "</tr>";
                        echo "</form>";
                    }
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                    echo "<tr>";
                    echo "<td>add new record</td>";
                    echo "<td><textarea name='department_name' placeholder='department name'></textarea></td>";
                    echo "<td colspan='2'><input type='submit' name='addRow' class='btn btn-info' value='add row'/> </td>";
                    echo " <input type='hidden' name='tableName' value='departments_info'/> "; //** */
                    echo "</tr>";
                    echo "</form>";

                } catch (PDOException $ex) {
                    echo $ex->getMessage();
                } ?>
                    </tbody>
                </table>
        </section>
        <div class="container ">
            <footer class="py-3 my-4">
                <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                    <li class="nav-item"><a href="main.php" class="nav-link px-2 text-muted">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
                    <li class="nav-item"><a href="main.php#aboutus" class="nav-link px-2 text-muted">About</a></li>
                    <li class="nav-item"><a href="main.php#contactus" class="nav-link px-2 text-muted">Contact US</a>
                    </li>
                </ul>
                <p class="text-center text-muted">© 2021 Company, Inc</p>
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </div>
</body>

</html>