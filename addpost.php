<?php
require_once('header.php');
require_once('navigation.php');
?>
      
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1>Tuko news</h1>
                            <span class="subheading">create new post</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
         <?php
         require_once('connectvars.php');

    
        
                    if (!isset($_SESSION['user_id'])) {
                echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
                exit();
            } 

            $user_id = $_SESSION['user_id'];

// Connect to MySQL
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query topics table
$sql = "SELECT id, topic FROM topics";
$result = mysqli_query($dbc, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($dbc));
}
?>
 
<?php

         
         if (!$dbc){
            die("Error connecting to database: ".mysqli_connect_error());
         }

         if (isset($_POST['submit_post'])){
            //Grab the profile data from the POST
            $topic_id = mysqli_real_escape_string($dbc, $_POST['topic_id']);

            $title = mysqli_real_escape_string($dbc, trim($_POST['title']));
            $body = mysqli_real_escape_string($dbc, trim($_POST['body']));
            $image = $_FILES['image'];

            $targetDir = "uploads/";
            $imageName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $imageName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            // Validate the form data that the user submitted
            if (empty($topic_id)){
                die('topic id is required.');
            }

            if (empty($title)){
                die('title is required.');
            }

            if (empty($body)){
                die('body is required.');
            }

            if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
                die("No image uploaded or there was an error uploading the file.");
            }

           $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                die("File is not an image.");
            }

            // Check file size (max 2MB)
            if ($_FILES["image"]["size"] > 2000000) {
                die("Sorry, your file is too large.<br>");
            }

            // Allow certain file formats
            if (!in_array($imageFileType, $allowedTypes)) {
                die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>");
            }

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                die("The was a problem uploading your image.");
            }

           
            $query = "INSERT INTO posts(user_id, topic_id, title, body, image, created_at) VALUES" .
                "('$user_id', '$topic_id', '$title', '$body', '$imageName', NOW())";
                
            if (mysqli_query($dbc, $query)){
                //Confirm success with the user
                echo '<p>Your new post has been added.</p>';
                
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
                        <div class="my-5">
                           
                          <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <label for="topic">Choose a Topic:</label>
                                <select name="topic_id" id="topic">
                                    <option value="">-- Select Topic --</option>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <option value="<?= htmlspecialchars($row['id']) ?>">
                                            <?= htmlspecialchars($row['topic']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            <fieldset>
                                <div class="form-floating">
                                    <input class="form-control" id="title" name="title" type="text" placeholder="Enter your title..."/>
                                    <label for="title">Title:</label>
                                </div><br/>
                                <p>Body:</p>
                                <textarea name="body" rows="10" cols="60"></textarea>
                                    
                                <div class="form-floating">
                                            <label for="image">image:</label><br/><br/>
                                            <input type="file" id="image" name="image" />
                                    </fieldset>
                                </div>
                                <br /><br/>
                                 <button id="submitButton" type="submit" name="submit_post" class="btn btn-primary">post</button>
                            </form>
        
        <!-- Footer-->
          <?php
                require_once('footer.php');
                ?>       
        