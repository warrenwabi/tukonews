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
                            <span class="subheading">delete your post</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
         <?php
            require_once('connectvars.php');

            if (!isset($_SESSION['user_id'])) {
                die("Please log in first.");
            }

            if (!isset($_GET['post_id'])) {
                die("No post ID provided.");
            }

            $user_id = $_SESSION['user_id'];
            $post_id = intval($_GET['post_id']);

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            // Confirm the post belongs to the user
            $check = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($dbc, $check);
            mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                die("Unauthorized or post not found.");
            }

            // Delete the post
            $delete = "DELETE FROM posts WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($dbc, $delete);
            mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);

            if (mysqli_stmt_execute($stmt)) {
                echo "Post deleted successfully.";
                // Redirect back
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                echo "Error deleting post.";
            }

            mysqli_close($dbc);
        ?>
            
</body> 
</html>
