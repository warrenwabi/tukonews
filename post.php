<?php
require_once('header.php');
require_once('navigation.php');
?>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/post-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="post-heading">
                            <h1>posts from other poeple</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Post Content-->
       <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- Post preview-->
                    <div class="post-preview">
                       <?php
            
            if (!isset($_SESSION['user_id'])) {
             // Redirect to login if not logged in
             header("Location: login.php");
             exit();
}
            $user_id = $_SESSION['user_id']; // Make sure user is logged in

            // Connect to DB
            require_once('connectvars.php');
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if (!$dbc) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch posts by this user
            $query = "SELECT id, title, body, image, created_at FROM posts WHERE user_id != $user_id ORDER BY created_at DESC";
            $result = mysqli_query($dbc, $query);

            if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                    echo '<div class="post-preview">';
                    echo '<a href="moreinfo.php?id=' . $row['id'] . '">';
                    echo '<h2 class="post-title">' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<h3 class="post-subtitle">' . htmlspecialchars(substr($row['body'], 0, 100)) . '...</h3>';
                    echo '</a>';
                    echo '<p class="post-meta">Posted on ' . date("F d, Y", strtotime($row['created_at'])) . '</p>';
                    if (!empty($row['image'])) {
                        echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" class="img-fluid mb-3" />';
                    }
                    echo '</div>';
                }
            } else {
                echo "<p>Error fetching posts: " . mysqli_error($dbc) . "</p>";
            }

            mysqli_close($dbc);
        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-->
           <?php
                require_once('footer.php');
                ?>  
