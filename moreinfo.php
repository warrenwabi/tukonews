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
                            <h1>Tuko news</h1>
                            <h2 class="subheading">know more</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Post Content-->
        <article class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                       <?php
                        require_once('connectvars.php');
                       
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                        if (!$dbc) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                            
                        if (!isset($_GET['id']) && is_numeric(!$_GET['id'])) {
                            die("Invalid post ID");
                        }
                            $post_id = $_GET['id'];
                            $user_id = $_GET['id'];
                            $query = "SELECT title, body, image, created_at FROM posts WHERE id = $post_id";
                            $result = mysqli_query($dbc, $query);

                            if (mysqli_num_rows($result) !== 1) {
                                die("Invalid post ID");
                        }
                                $row = mysqli_fetch_array($result);
                                echo '<h1>' . htmlspecialchars($row['title']) . '</h1>';
                                echo '<p><em>Posted on ' . date("F d, Y", strtotime($row['created_at'])) . '</em></p>';
                                if (!empty($row['image'])) {
                                    echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" class="img-fluid mb-3" />';
                                }
                                echo '<p>' . nl2br(htmlspecialchars($row['body'])) . '</p>';

        // === START COMMENT SECTION ===
        echo '<hr><h4>Comments</h4>';
        $comment_query = "SELECT * FROM comments INNER JOIN posts ON posts.id = comments.post_id INNER JOIN users ON users.id = posts.user_id WHERE post_id=$post_id";
         $comment_result = mysqli_query($dbc, $comment_query);

        if (!$comment_result) {
             echo '<p style="color:red;">Query failed: ' . mysqli_error($dbc) . '</p>';
        } else {
            if (mysqli_num_rows($comment_result) > 0) {
                while ($comment = mysqli_fetch_assoc($comment_result)) {
                    echo '<div class="mb-3 p-3 border rounded">';
                    echo '<strong>commented by: ' . htmlspecialchars($comment['name']) . '</strong> ';
                    echo '<em>(' . date("F d, Y H:i", strtotime($comment['created_at'])) . ')</em>';
                    echo '<p>' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No comments yet.</p>';
            }
        }

        echo '
        <hr>
        <h5>Leave a Comment</h5>
        <form action="" method="post">
            <div class="mb-3">
                <textarea name="comment" class="form-control" rows="3" placeholder="Your comment..." required></textarea>
            </div>
            <button type="submit" name="submit_comment" class="btn btn-primary">Post Comment</button>
        </form>';
        // === END COMMENT SECTION ===
                           
                        
if (isset($_POST['submit_comment'])) {
    $comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
    
    if (!empty($comment)) {
        $insert_query = "INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, '$user_id', '$comment')";
        if (mysqli_query($dbc, $insert_query)) {
            echo "<script>window.location.href = 'moreinfo.php?id=$post_id';</script>";
        } else {
            echo '<p>Error posting comment: ' . mysqli_error($dbc) . '</p>';
        }
    } else {
        echo '<p>Please fill in all fields.</p>';
    }
}
                        mysqli_close($dbc);
                        ?>
                            
                    <?php
                require_once('footer.php');
                ?>       
        
