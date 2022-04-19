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
    // global variable to check validation 
    $isValid = true;

    // define variables for form data
    $fName = $lName  = $email  = $password = $cPassword  = $address = $phoneNumber = "";
    //define variables for error message
    $fNameErr = $lNameErr = $emailErr  = $pswdErr = $cPswdErr = $addressErr = $phoneNumberErr = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // first name validation
        if (empty($_POST['fName'])) {
            $fNameErr = "First Name is required!";
            $isValid = false;
        } else {

            $fName = test_input($_POST['fName']);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/", $fName)) {
                $fNameErr = "Only letters and white spaces are allowed!";
                $isValid = false;
            }
        }

        // last name validation
        if (empty($_POST['lName'])) {
            $lNameErr = "Last Name is required!";
            $isValid = false;
        } else {

            $lName = test_input($_POST['lName']);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/", $lName)) {
                $lNameErr = "Only letters and white spaces are allowed!";
                $isValid = false;
            }
        }

        // email validation
        if (empty($_POST['email'])) {

            $emailErr = "Email is required!";
            $isValid = false;
        } else {

            $email = test_input($_POST['email']);

            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid Email Address!";
                $isValid = false;
            }
        }


        // password validation
        if (empty($_POST['password'])) {
            $pswdErr = "password is required!!";
            $isValid = false;
        } else {
            $password = test_input($_POST['password']);
        }

        // confirm password validation
        if (empty($_POST['cPassword'])) {
            $cPswdErr = "confrim password is required!!";
            $isValid = false;
        } else {
            $cPassword = test_input($_POST['cPassword']);
        }


        // check password and the confirm password whether is matching or not
        if ($password != $cPassword) {
            $pswdErr = "password and confrim password are not matching";
            $cPswdErr = "";
            $isValid = false;
        }

        // address validation
        if (empty($_POST['address'])) {
            $addressErr = "City Name is required!";
            $isValid = false;
        } else {

            $address = test_input($_POST['address']);
        }


        // last name validation
        if (empty($_POST['phoneNumber'])) {
            $phoneNumberErr = "phone number is required!";
            $isValid = false;
        } else {

            $phoneNumber = test_input($_POST['phoneNumber']);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[0-9]{0,15}$/", $phoneNumber)) {
                $phoneNumberErr = "Only numbers are allowed!";
                $isValid = false;
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


    <!-- ======= Header ======= -->
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


        <div class="modal modal-signin position-static d-block  py-5" tabindex="-1" role="dialog" id="modalSignin">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-5 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <!-- <h5 class="modal-title">Modal title</h5> -->
                        <h2 class="fw-bold mb-0">Sign up</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-5 pt-0">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-4" name="fName" id="fName" placeholder="First Name" value="<?php echo $fName; ?>">
                                <label for="fName">First Name</label>
                                <div class="form-text text-danger"><?php echo $fNameErr; ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-4" name="lName" id="lName" placeholder="Last Name" value="<?php echo $lName; ?>">
                                <label for="lName">Last Name</label>
                                <div class="form-text text-danger"><?php echo $lNameErr; ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-4" name="email" id="email" placeholder="Email Address" value="<?php echo $email; ?>">
                                <label for="email">Email Address</label>
                                <div class="form-text text-danger"><?php echo $emailErr; ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-4" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>"> <label for="password">Password</label>
                                <div class="form-text text-danger"><?php echo $pswdErr;  ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-4" name="cPassword" id="cPassword" placeholder="Confirm Password" value="<?php echo $cPassword; ?>">
                                <label for="cPassword">Confirm Password</label>
                                <div class="form-text text-danger"><?php echo $cPswdErr;  ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-4" name="address" id="address" placeholder="Address" value="<?php echo $address; ?>"> <label for="address">Address</label>
                                <div class="form-text text-danger"><?php echo $addressErr; ?></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-4" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" value="<?php echo $phoneNumber; ?>"> <label for="phoneNumber">Phone Number</label>
                                <div class="form-text text-danger"><?php echo $phoneNumberErr; ?></div>
                            </div>
                            <button class="w-100 mb-2 btn btn-lg rounded-4 btn-primary" type="submit">Sign Up</button>
                            <small class="text-muted">By clicking Sign up, you agree to the terms of use.</small>
                            <hr class="my-4">
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
                    <li class="nav-item"><a href="main.php#contactus" class="nav-link px-2 text-muted">Contact US</a>
                    </li>
                </ul>
                <p class="text-center text-muted">© 2021 Company, Inc</p>
            </footer>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" &&  $isValid) {

        // variable for database connection
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

            // $stmt = $pdo->query('SELECT * FROM user_information');
            $stmt = $conn->query("select * from patient_info");
            foreach ($stmt as $row) {
                if ($email == $row['email']) {
                    $emailExist = true;
                }
            }

            $stmt = $conn->query("select * from doctors_info");
            foreach ($stmt as $row) {
                if ($email == $row['email']) {
                    $emailExist = true;
                }
            }

            $stmt = $conn->query("select * from admin_info");
            foreach ($stmt as $row) {
                if ($email == $row['email']) {
                    $emailExist = true;
                }
            }

            if (!empty($_POST['password'])) {
                $password = test_input($_POST['password']);
            }

            if (!$emailExist) {

                // prepared statement created with sql query
                $stmt = $conn->prepare("INSERT INTO patient_info (first_name,last_name,email,password,address,phone_number)  VALUES (?, ?, ?, ?, ?, ?)");
                // assing the parameters
                $stmt->bindParam(1, $fName, PDO::PARAM_STR);
                $stmt->bindParam(2, $lName, PDO::PARAM_STR);
                $stmt->bindParam(3, $email, PDO::PARAM_STR);
                $stmt->bindParam(4, $password, PDO::PARAM_STR);
                $stmt->bindParam(5, $address, PDO::PARAM_STR);
                $stmt->bindParam(6, $phoneNumber, PDO::PARAM_STR);
                // execute the query
                $stmt->execute();

                $count = $stmt->rowCount();
            }

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" &&  $isValid) {
        if ($count > 0) {
    ?>
            <script type="text/javascript">
                window.onload = function() {
                    document.getElementById("mybutton1").click();
                }
            </script>

        <?php } else {

        ?>
            <script type="text/javascript">
                window.onload = function() {
                    document.getElementById("mybutton2").click();
                }
            </script>
    <?php }
    } ?>


    <!-- Button trigger modal -->
    <button type="button" id="mybutton1" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" hidden>
        Launch static backdrop modal
    </button>

    <button type="button" id="mybutton2" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" hidden>
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
                    You Signed Up Successfully
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary" href="makeappointment.php" role="button">Go to my page</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Email already Exist. Please Enter another email
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>