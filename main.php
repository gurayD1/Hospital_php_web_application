<?php
// Start the session
session_start();
$isValid = true;

$email = $userPassword = "";
$emailErr = $passwordErr = "";
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>

</head>

<body>
    <?php
    $isValidContactUs = true;

    $myEmail = $fName = "";
    $lName = $message = "";

    function test_input2($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_POST['contactUs']) && $_SERVER['REQUEST_METHOD'] == "POST") {

        if (empty($_POST['fName'])) {
            // $emailErr = "Email is required!";
            $isValidContactUs = false;
        } else {
            $fName = test_input2($_POST['fName']);
        }

        if (empty($_POST['lName'])) {
            // $emailErr = "Email is required!";
            $isValidContactUs = false;
        } else {
            $lName = test_input2($_POST['lName']);
        }

        if (empty($_POST['myemail'])) {
            // $emailErr = "Email is required!";
            $isValidContactUs = false;
        } else {
            $myEmail = test_input2($_POST['myemail']);
        }

        if (empty($_POST['message'])) {
            // $emailErr = "Email is required!";
            $isValidContactUs = false;
        } else {
            $message = test_input2($_POST['message']);
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" &&  $isValidContactUs) {
            echo "working";
            // variable for database connection
            $servername = "localhost:3308";
            $username = "root4";
            $password = "";
            $dbname = "php_hospital_db";


            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("INSERT INTO contact_us (first_name,last_name,email_address,user_message)  VALUES ( ?, ?,?,?)");
                $stmt->bindParam(1, $fName, PDO::PARAM_STR);
                $stmt->bindParam(2, $lName, PDO::PARAM_STR);
                $stmt->bindParam(3, $myEmail, PDO::PARAM_STR);
                $stmt->bindParam(4, $message, PDO::PARAM_STR);
                $stmt->execute();
    ?>
                <script type="text/javascript">
                    window.onload = function() {
                        document.getElementById("mybutton3").click();
                    }
                </script>
            <?php

            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }

        if (!$isValidContactUs) { ?>
            <script type="text/javascript">
                window.onload = function() {
                    document.getElementById("mybutton2").click();
                }
            </script>

    <?php }
    }
    ?>


    <?php
    if (isset($_POST['logInsection']) && $_SERVER['REQUEST_METHOD'] == "POST") {
        $isValid = true;

        $email = $userPassword = "";
        $emailErr = $passwordErr = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            if (empty($_POST['email'])) {
                $emailErr = "Email is required!";
                $isValid = false;
            } else {
                $email = test_input2($_POST['email']);
            }

            if (empty($_POST['password'])) {
                $passwordErr = "Password is required!";
                $isValid = false;
            } else {
                $userPassword = test_input2($_POST['password']);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" &&  $isValid) {

            // variable for database connection
            $servername = "localhost:3308";
            $username = "root4";
            $password = "";
            $dbname = "php_hospital_db";
            $emailExist = false;
            $passwordCorrect = false;

            // try catch block to establish with the database and display error message if occurs
            try {
                // connect to the database
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // $stmt = $pdo->query('SELECT * FROM user_information');
                $stmt = $conn->query("select * from patient_info");
                foreach ($stmt as $row) {
                    if ($email == $row['email']) {
                        $emailExist = true;
                        if ($userPassword == $row['password']) {
                            $passwordCorrect = true;

                            $_SESSION["firstName"] = $row['first_name'];
                            $_SESSION["LastName"] = $row['last_name'];
                            $_SESSION["userId"] = $row['patient_id'];
                            $_SESSION["accountType"] = "patient";
                        }
                    }
                }

                if (!$emailExist) {
                    $stmt = $conn->query("select * from doctors_info");
                    foreach ($stmt as $row) {
                        if ($email == $row['email']) {
                            $emailExist = true;
                            if ($userPassword == $row['password']) {
                                $passwordCorrect = true;

                                $_SESSION["firstName"] = $row['first_name'];
                                $_SESSION["LastName"] = $row['last_name'];
                                $_SESSION["userId"] = $row['doctor_id'];
                                $_SESSION["accountType"] = "doctor";
                            }
                        }
                    }
                }

                if (!$emailExist) {
                    $stmt = $conn->query("select * from admin_info");
                    foreach ($stmt as $row) {
                        if ($email == $row['email']) {
                            $emailExist = true;
                            if ($userPassword == $row['password']) {
                                $passwordCorrect = true;

                                $_SESSION["firstName"] = $row['first_name'];
                                $_SESSION["LastName"] = $row['last_name'];
                                $_SESSION["userId"] = $row['admin_id'];
                                $_SESSION["accountType"] = "admin";
                            }
                        }
                    }
                }

                if (!$emailExist) {
                    $emailErr = "Email does not exist, Please check it";
                } else if (!$passwordCorrect) {
                    $passwordErr = "password is not correct. please check it";
                } else {

                    $_SESSION["currentUser"] = $email;
    ?>

                    <script type="text/javascript">
                        window.onload = function() {
                            document.getElementById("mybutton1").click();
                        }
                    </script>

    <?php
                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    }
    // remove whitespace etc.
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ?>

    <!-- Button trigger modal -->
    <button type="button" id="mybutton1" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" hidden>
        Launch static backdrop modal
    </button>
    <button type="button" id="mybutton2" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" hidden>
        Launch static backdrop modal
    </button>
    <button type="button" id="mybutton3" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" hidden>
        Launch static backdrop modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You Logged in Successfully
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?php if ($_SESSION["accountType"] == 'admin') { ?>
                        <a class="btn btn-primary" href="admin_page.php" role="button">Go to my page</a>
                    <?php } else if ($_SESSION["accountType"] == 'doctor') {   ?>
                        <a class="btn btn-primary" href="doctor_page.php" role="button">Go to my page</a>
                    <?php } else if ($_SESSION["accountType"] == 'patient') {  ?>
                        <a class="btn btn-primary" href="makeappointment.php" role="button">Go to my page</a>

                    <?php } else {  ?>
                        <a class="btn btn-primary" href="main.php" role="button">Go to my page</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please don't leave any section empty
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    you send the message successfully
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container-xxl">

        <!-- ======= Header ======= -->
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

        <div class="card  text-center m-2">
            <img src="images/background1.jpg" class="card-img" alt="...">
            <div class="card-img-overlay">
                <div class="col-md-5 p-lg-5 mx-auto">
                    <h1 class="card-title display-4 fw-bold"><i class="fa-solid fa-circle-h"></i>PhP-Hospital</h1>
                    <p class="card-text fs-2">You are in good hands.</p>
                </div>
            </div>
        </div>
        <div class="container  p-3 " id="aboutus">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-8 ">
                    <img src="images/Doctors.jpg" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" loading="lazy">
                </div>
                <div class="col-lg-4">
                    <h1 class="display-6 fw-bold lh-1 mb-3">Welcome to the PhP-Hospitals</h1>
                    <p class="lead">It is a long established fact that a reader will be distracted by the readable
                        content more or less normal distribution of letters opposed.</p>
                    <h2 class="display-7 fw-bold lh-1 mb-3">Our Mission</h2>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <p class="lead ">Reader will be distracted by the readable content of a page when looking at its
                            layout the point of using more or less normal distribution.</p>
                    </div>
                    <h2 class="display-7 fw-bold lh-1 mb-3">Our Vision</h2>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <p class="lead">Explain to you how all this mistaken idea of denouncing pleasure.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======= Hero Section ======= -->

        <div class="container px-4 py-5 " id="custom-cards">
            <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-2">
                <div class="col">
                    <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('images/doktor5.jpg'); background-size: cover;   ">
                        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                            <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Responsible</h2>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('images/nurse1.jpg'); background-size: cover; ">
                        <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                            <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Superfast</h2>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('images/reception.jpg'); background-size: cover; ">
                        <div class="d-flex flex-column h-100 p-5 pb-3 text-shadow-1">
                            <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Service</h2>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ======= Hero Section ======= -->

        <div class="container px-4 py-5 " id="featured-3">
            <h2 class="pb-2 border-bottom">In Our Hospital</h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-4">
                <div class="feature col text-center ">
                    <div class=" ">
                        <h1><i class="fa-solid fa-user-doctor"></i></h1>
                    </div>
                    <h2>85 Doctors </h2>
                </div>
                <div class="feature col  text-center">
                    <div>
                        <h1><i class="fa-solid fa-building"></i></h1>
                    </div>
                    <h2>18 Departments</h2>
                </div>
                <div class="feature col  text-center">
                    <div>
                        <h1> <i class="fa-solid fa-flask"></i></h1>
                    </div>
                    <h2>12 Labs</h2>
                </div>
                <div class="feature col  text-center">
                    <div>
                        <h1> <i class="fa-solid fa-award"></i></h1>
                    </div>
                    <h2>150 Awards</h2>
                </div>
            </div>
        </div>

        <div class="container  p-5" id="departments">
            <h1 class="text-center mb-5">Departments</h1>
            <div class="row">
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="text-center">
                        <h1><i class="fa-solid fa-heart"></i> </h1>
                        <h3>Cardiology</h3>
                        <p>How all this mistaken al idea of denouncing pleasure praisings pain was complete.</p>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class=" text-center">

                        <h1><i class="fa-solid fa-lungs"></i> </h1>
                        <h3>Pulmonology</h3>
                        <p> Who chooses to enjoy a pleasure that has annoying consquences, or one who avoids a pain.</p>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="single-item text-center">
                        <div class="iocn-holder">
                            <span class="flaticon-neurology"></span>
                        </div>
                        <div class="text-holder">
                            <h1><i class="fa-solid fa-brain"></i> </h1>
                            <h3>Gynecology</h3>
                            <p> Who chooses to enjoy a pleasure that has annoying consquences, or one who avoids a pain.
                            </p>
                        </div>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class=" text-center">
                        <h1> <i class="fa-solid fa-mars"></i></i></h1>
                        <h3>Neurology</h3>
                        <p>How all this mistaken al idea of denouncing pleasure praisings pain was complete.</p>
                    </div>
                </div>
                <!--End single item-->
            </div>

            <div class="row">
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class=" text-center">
                        <h1><i class="fa-solid fa-mars"></i></h1>
                        <h3>Urology</h3>
                        <p> Who chooses to enjoy a pleasure that has annoying consquences, or one who avoids a pain.</p>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="text-center">
                        <h1><i class="fa-solid fa-heart-pulse"></i></h1>
                        <h3>Gastrology</h3>
                        <p> Who chooses to enjoy a pleasure that has annoying consquences, or one who avoids a pain.</p>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="text-center">
                        <h1><i class="fa-solid fa-stethoscope"></i></h1>
                        <h3>Pediatrician</h3>
                        <p> There anyone loves pursue or desires to obtain pain sed of itself because pain occasionally.
                        </p>
                    </div>
                </div>
                <!--End single item-->
                <!--Start single item-->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class=" text-center">
                        <h1><i class="fa-solid fa-flask"></i></h1>
                        <h3>Laboratory</h3>
                        <p> Take a trivial example, which of ever undertake laborous physically exercise some advantage.
                        </p>


                    </div>
                </div>
                <!--End single item-->
            </div>
        </div>

        <div class="container-xl col-xl-10 col-xxl-8 px-2 py-3" id="signin">
            <div class="row align-items-center g-lg-5 py-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-4 fw-bold lh-1 mb-3">Log In to your account quickly</h1>
                    <p class="col-lg-10 fs-4">you can log in to your account and make an appointment in any department
                    </p>
                </div>
                <div class=" mx-auto col-lg-6">
                    <form class="p-4 p-md-5 border rounded-3 bg-light" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email address" value="<?php echo $email; ?>" autocomplete="off">
                            <label for="email">Email address</label>
                            <div class="form-text text-danger"><?php echo $emailErr; ?></div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $userPassword; ?>" autocomplete="off">
                            <label for="password">Password</label>
                            <div class="form-text text-danger"><?php echo $passwordErr; ?></div>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <button class="w-100 btn btn-lg btn-primary" type="submit">Log In</button>
                        <a href="sign-up.php" class="w-100 btn btn-lg btn-primary mt-2" role="button" aria-pressed="true">Sign
                            Up</a>
                        <hr class="my-4">
                        <input type="hidden" name="logInsection" value="true" />
                        <small class="text-muted">By clicking Sign up, you agree to the terms of use.</small>
                    </form>
                </div>
            </div>
        </div>

        <div class=" container  w-50  ">
            <h1 class="text-center">Departments</h1>
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner ">
                    <div class="carousel-item active">
                        <img src="images/dentis.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block fw-bold">
                            <h5 class="fw-bold">Hospitals Providing Total</h5>
                            <p>Some representative placeholder content for the first slide.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/clinic.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block fw-bold">
                            <h5>Cardiac Clinic Analysisl</h5>
                            <p>Explain to you how all this mistaken idea of denouncing pleasure and praising pain was
                                born
                                and I will give you a complete account of the system, and the master-builder of human
                                happiness. Expound the actual teachings of the great explorer of the truth..</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/laboratory.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block fw-bold">
                            <h5>Laboratory Analysis</h5>
                            <p>Explain to you how all this mistaken idea of denouncing pleasure and praising pain was
                                born
                                and I will give you a complete account of the system, and the master-builder of human
                                happiness. Expound the actual teachings of the great explorer of the truth..</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/Hospital1.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block fw-bold">
                            <h5>Third slide label</h5>
                            <p>Some representative placeholder content for the third slide.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <br>
        <br>

        <div class="row p-3 " id="doctors">
            <div class="section-title">
                <h2 class="display-3">Doctors</h2>
                <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint
                    consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia
                    fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>
            <div class="col-lg-6">

                <img class="rounded-circle" alt="100x100" src="images/doctor1.jpg" data-holder-rendered="true">
                <h2>Walter White</h2>
                <p>Chief Medical Officer
                    Explicabo voluptatem mollitia et repellat qui dolorum quasi</p>

            </div>
            <div class="col-lg-6">
                <img class="rounded-circle" alt="100x100" src="images/doctor2.jpg" data-holder-rendered="true">

                <h2>William Anderson</h2>
                <p>Cardiology Quisquam facilis cum velit laborum corrupti fuga rerum quia.</p>

            </div>
            <div class="col-lg-6">
                <img class="rounded-circle" alt="100x100" src="images/doctor3.jpg" data-holder-rendered="true">

                <h2>
                    Sarah Jhonson</h2>
                <p>Anesthesiologist
                    Aut maiores voluptates amet et quis praesentium qui senda para</p>

            </div>
            <div class="col-lg-6">
                <img class="rounded-circle" alt="100x100" src="images/doctor4.jpg" data-holder-rendered="true">

                <h2>Amanda Jepson</h2>
                <p>Neurosurgeon
                    Dolorum tempora officiis odit laborum officiis et et accusamus.</p>

            </div>
        </div>

        <div class=" container   p-5 " style="width: 35%;" id="comments">
            <h1 class="text-center">What Our Clients Say</h1>
            <div id="carouselExampleCaptions1" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/person3.jpg" class="d-block w-100  " alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Rossy Miranda</h5>
                            <p class="fw-bold">Mistaken idea of denouncing pleasure and praising pain was born and I
                                will give you a
                                complete account of the system.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/person4.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Peter Lawrence</h5>
                            <p>The master-builder of human happiness one rejects, dislikes, or avoids pleasure itself,
                                because it is pleasure pursue.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/woman2.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Name</h5>
                            <p>Comment</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/woman3.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Name</h5>
                            <p>Comment.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!--Section: Contact v.2-->
        <section class="mb-4 p-5 " id="contactus">

            <!--Section heading-->
            <h2 class="h1-responsive font-weight-bold text-center my-4">Contact us</h2>
            <!--Section description-->
            <p class="text-center w-responsive mx-auto mb-5">Do you have any questions? Please do not hesitate to
                contact us directly. Our team will come back to you within
                a matter of hours to help you.</p>

            <div class="row justify-content-around ">

                <!--Grid column-->
                <div class="col-md-6  ">
                    <form id="contact-form" name="contact-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                        <div class="row mb-3">
                            <div class="col">
                                <input type="text" class="form-control" name="fName" placeholder="First name" aria-label="First name">
                            </div>
                            <div class="col">
                                <input type="text" name="lName" class="form-control" placeholder="Last name" aria-label="Last name">
                            </div>
                        </div>
                        <div class="mb-3">

                            <input type="email" class="form-control" id="exampleFormControlInput1" name="myemail" placeholder="Email address">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Your Message</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="message" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="contactUs" value="true" />
                        <button type="submit" class="btn btn-primary">Send it</button>
                    </form>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-3 text-center">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-map-marker-alt fa-2x"></i>
                            <p>San Francisco, CA 94126, USA</p>
                        </li>

                        <li><i class="fas fa-phone mt-4 fa-2x"></i>
                            <p>+ 01 234 567 89</p>
                        </li>

                        <li><i class="fas fa-envelope mt-4 fa-2x"></i>
                            <p>contact@mdbootstrap.com</p>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->

            </div>

        </section>
        <!--Section: Contact v.2-->

        <div class="container p-5 " style="background-image:url(images/awards-bg.jpg); color: white;">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-holder">
                        <div class="sec-title">
                            <h1>You’re in Good Hands</h1>
                        </div>
                        <div class="text">
                            <p>We believe in bringing the most modern techniques and delivering extraordinary care to
                                ailing population with the highest levels of ethics and standards. We are committed to
                                continuing medical education, through our fellowship and DNB programs.</p>
                            <p>We organize atleast one conference a month and support research foundation for continued
                                advancement.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="awards-holder">
                        <div class="sec-title">
                            <h1>Clinic Awards</h1>
                        </div>
                        <div class="row">
                            <!--Start single item-->
                            <div class="col-md-6">
                                <div class="single-item">
                                    <a href="#"><img src="images/awards1.png" alt="Awesome Brand Image"></a>
                                </div>
                            </div>
                            <!--End single item-->
                            <!--Start single item-->
                            <div class="col-md-6">
                                <div class="single-item">
                                    <a href="#"><img src="images/awards2.png" alt="Awesome Brand Image"></a>
                                </div>
                            </div>
                            <!--End single item-->

                            <!--Start single item-->
                            <div class="col-md-6">
                                <div class="single-item">
                                    <a href="#"><img src="images/awards3.png" alt="Awesome Brand Image"></a>
                                </div>
                            </div>
                            <!--End single item-->
                            <!--Start single item-->
                            <div class="col-md-6">
                                <div class="single-item">
                                    <a href="#"><img src="images/awards4.png" alt="Awesome Brand Image"></a>
                                </div>
                            </div>
                            <!--End single item-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
    </div>
</body>

</html>