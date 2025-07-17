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
                            <span class="subheading">Be informed</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
         <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- Post preview-->
        <div class="post-preview">

           <?php
                // Connect to the database
                require_once('connectvars.php');
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                // Check connection
                if (!$dbc) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Query to fetch posts
               $dbquery = "SELECT id, title, body, image, created_at FROM posts ORDER BY created_at DESC";
                $result = mysqli_query($dbc, $dbquery);

                // Display posts
                while ($row = mysqli_fetch_array($result)) {
                    echo '<div class="post-preview">';
                    echo '<a href="moreinfo.php?id=' . $row['id'] . '">';
                    echo '<h2 class="post-title">' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<h3 class="post-subtitle">' . htmlspecialchars(substr($row['body'], 0, 100)) . '...</h3>';
                    echo '</a>';
                    echo '<p class="post-meta">';
                    echo 'Posted on ' . date("F d, Y", strtotime($row['created_at']));
                    echo '</p>';
                    if (!empty($row['image'])) {
                        echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" class="img-fluid mb-3" />';
                    }
                    echo '</div>';
                }

                mysqli_close($dbc);
        ?>
                <?php
                require_once('footer.php');
                ?>       
        