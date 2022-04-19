<?php
// Start the session
session_start();
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
    <?php
    $isValid = true;
    $email = $userPassword = "";
    $emailErr = $passwordErr = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (empty($_POST['email'])) {
            $emailErr = "Email is required!";
            $isValid = false;
        } else {
            $email = test_input($_POST['email']);
        }

        if (empty($_POST['password'])) {
            $passwordErr = "Password is required!";
            $isValid = false;
        } else {
            $userPassword = test_input($_POST['password']);
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
        <div class="container   ">
            <div class="modal-lg  mx-auto">
                <div class="modal-lg-dialog ">
                    <div class="modal-content rounded-5 shadow text-center">
                        <div class="modal-header    ">
                            <h2 class="mx-auto my-auto   ">Sign in</h2>
                        </div>
                        <div class="modal-body ">
                            <form class="row justify-content-center" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <div class="col-7">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control rounded-4" name="email" id="email" placeholder="Email address" value="<?php echo $email; ?>">
                                        <label for="email">Email address</label>
                                        <div class="form-text text-danger"><?php echo $emailErr; ?></div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control rounded-4" name="password" id="password" placeholder="Password" value="<?php echo $userPassword; ?>">
                                        <label for="password">Password</label>
                                        <div class="form-text text-danger"><?php echo $passwordErr; ?></div>
                                    </div>
                                    <button class="w-50 mb-2 btn btn-lg rounded-4 btn-primary" type="submit">Sign
                                        in</button>
                                    <div class="w-100"></div>
                                    <small class="text-muted ">By clicking Sign up, you agree to the terms of
                                        use.</small>
                                    <div class="modal-footer mt-4">
                                        <h2>Don't You Have an Account?</h2>
                                        <div class="w-100"></div>
                                        <a class="w-50 mt-2 btn btn-lg rounded-4 btn-primary border mx-auto" href="sign-up.php" role="button">Sign Up</a>
                                        <div class="w-100"></div>
                                    </div>
                                </div>
                            </form>
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
                        <li class="nav-item"><a href="main.php#contactus" class="nav-link px-2 text-muted">Contact
                                US</a>
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