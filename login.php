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
                            <h1>Login</h1>
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

         if (isset($_POST['login'])){

          // Grab the user-entered log-in data
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));

        // Validate the form data that the user submitted
        if (empty($email)){
            die('Email is required.');
        }

        if (empty($password1)){
            die('Password is required.');
        }
           
        // Look up the username and password in the database

        $query = "SELECT * FROM users WHERE email = '$email'";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
            $row = mysqli_fetch_array($data);
            // die($row['password']);
          if (password_verify($password1, $row['password'])){
            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            setcookie('id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
            setcookie('name', $row['name'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
            setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
            header('Location: index.php');
            exit();
          } else {
            die('Sorry, you must enter a valid password to log in.');
          }
        } else {
          die('Sorry, you must enter a valid email to log in.');
        }
         }
         mysqli_close($dbc);
         ?>
        <main class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <p>Want to get in touch? Login the form below to send me a message and I will get back to you.</p>
                        <div class="my-5">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <fieldset>
                                <div class="form-floating">
                                    <input class="form-control" id="email" type="email" name="email" placeholder="Enter your email..."/>
                                    <label for="email">Email address</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="password1" type="password" name=password1 placeholder="Enter your password..."/>
                                    <label for="password1">password</label>
                                </div>
                                </fieldset>
                                </div>
                                <br />
                                <button id="submitButton" type="submit" name="login" class="btn btn-primary">Login</button>
                            </form>
                       
        </main>
        <!-- Footer-->
         <?php
                require_once('footer.php');
                ?>       
        