<?php
require_once('header.php');
require_once('navigation.php');
?>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/contact-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="page-heading">
                            <h1>Register yourself</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
         <?php
         require_once('connectvars.php');   

         //Connect to database
         $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

         if (!$dbc){
            die("Error connecting to database: ".mysqli_connect_error());
         }

         if (isset($_POST['register'])){
            //Grab the profile data from the POST

            $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
            $phone = mysqli_real_escape_string($dbc, trim($_POST['phone']));
            $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
            $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

            // Validate the form data that the user submitted
            if (empty($name)){
                die('Name is required.');
            }

            if (empty($phone)){
                die('Phone is required.');
            }

            if (empty($email)){
                die('Email is required.');
            }

            if (empty($password1)){
                die('Password is required.');
            }

            if (empty($password2)){
                die('Password confirmation is required.');
            }

            // Validate if password length > 3 characters
            if (strlen($password1) <= 3) {
                die("Password must be longer than 3 characters.");
            }

            //validate if password1 and 2 are the same
            if ($password1 !== $password2){
                die('Password mismatch');
            }

            // Check if the phone number is taken
            $phoneLookupQuery = "SELECT * FROM users where phone_number = '$phone'";
            $results = mysqli_query($dbc, $phoneLookupQuery);
            if (mysqli_num_rows($results) > 0){
                die("Phone number already taken.");
            }

            // Validate if email address is valid
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Invalid email format:");
            }
            // Check if the email is taken
            $emailLookupQuery = "SELECT * FROM users where email = '$email'";
            $results = mysqli_query($dbc, $emailLookupQuery);
            if (mysqli_num_rows($results) > 0){
                die("email number already taken.");
            }
            $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

            $query = "INSERT INTO users(name, phone_number, email, password, created_at) VALUES" .
                "('$name', '$phone', '$email', '$hashed_password', NOW())";
                
            if (mysqli_query($dbc, $query)){
                //Confirm success with the user
                echo '<p>Your new account has been successfully created.</p>';
                
                mysqli_close($dbc);
                exit();
            } else {
                echo "Error creating record: ".mysqli_error($dbc);
            }
         }
         mysqli_close($dbc);
         ?>
        <main class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <p>Want to get in touch? Register yourself in the form below to send me a message and I will get back to you as soon as possible!</p>
                        <div class="my-5">
                           
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <fieldset>
                                <div class="form-floating">
                                    <input class="form-control" id="name" type="text" name="name" placeholder="Enter your name..." />
                                    <label for="name">Name</label>
                                    <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="phone" type="phone" name="phone" placeholder="Enter your phone number..." />
                                    <label for="phone">phone number</label>
                                    <div class="invalid-feedback" data-sb-feedback="phone:required"> number is required.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="email" type="email" name="email" placeholder="Enter your email..." />
                                    <label for="email">Email address</label>
                                    <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
                                    <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="password1" type="password" name=password1 placeholder="Enter your password..."  />
                                    <label for="password1">password</label>
                                    <div class="invalid-feedback" data-sb-feedback="password:required">password number is required.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="password2" type="password" name="password2" placeholder="Enter your password..." />
                                    <label for="password2"> confirm password</label>
                                    <div class="invalid-feedback" data-sb-feedback="password:required">password number is required.</div>
                                </fieldset>
                                </div>
                                <br />
                               
                                <div class="d-none" id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">Form submission successful!</div>
                                        To activate this form, sign up at
                                        <br />
                                        <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                    </div>
                                </div>
                               
                                <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                                <!-- Submit Button-->
                                <button id="submitButton" type="submit" name="register" class="btn btn-primary">register</button>
                            </form>
                       <?php
                require_once('footer.php');
                ?>  
