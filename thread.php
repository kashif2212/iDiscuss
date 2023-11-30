<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <title>iDiscuss</title>
</head>

<body>
    <!-- <h1>Hello, world!</h1> -->
    <?php include 'partials/_dbconnect.php'?>
    <?php include 'partials/_header.php'?>
    <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `thread` WHERE thread_id=$id";
        $result = mysqli_query($conn,$sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['thread_id'];
            $thread_title = $row['thread_title'];
            $thread_desc = $row['thread_desc'];
            $thread_user_id = $row['thread_user_id'];

            // Query the users table to find out the name of user
            $sql2 = "SELECT user_email FROM `users` WHERE sno = '$thread_user_id'";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $posted_by = $row2['user_email'];

            // $posted_by = '';
            // if (!isset($posted_by)) {
            //     $posted_by = $row2['user_email'];
            // }

        }
        
        
    ?>
    <?php
    $showAlert = false;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            //Insert into comment database
            $comment = $_POST['comment']; // here comment is taken from the form and the name given is comment in the form for that particular div
            $comment = str_replace("<" , "&lt;" , $comment);
            $comment = str_replace(">" , "&gt;" , $comment);
            $sno = $_POST['sno'];
            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp());";
            $result = mysqli_query($conn,$sql);
            $showAlert = true;
            if ($showAlert) {
            
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has been added!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
    ?>

    
    <div class="container">
        <div class="jumbotron text-dark my-5" style="background-color:rgb(199, 199, 199); padding:50px;">
            <h2 class="display-4 text-center" style="font-weight:normal; margin-bottom: 17px; padding-bottom:12px;"><?php echo $thread_title; ?></h2>
            <p class="lead text-dark" style="font-size:24px; font-weight:normal;"><?php echo $thread_desc ;?></p>
            <hr class="my-4">
            <p style="font-size:18px;">No Spam / Advertising / Self-promote in the forums is not allowed. Do not postcopyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post questions. Remain respectful of other members at all times.</p>
            <!-- <a href="#" class="btn btn-success btn-lg" role="button">Learn More</a> -->
            <p><b>Posted by: <?php echo $posted_by ;?></b></p>

        </div>
    </div>

    <!-- <div class="container">
        <h1>Post a comment</h1>
        <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">

            <div class="form-group">
                <label for="exampleFormControlTextarea1" style="margin-top: 7px;margin-bottom: 7px;">Type your
                    comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success my-2 ">Post Comment</button>
        </form>
    </div> -->

<?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
  echo  '<div class="container">
  <h1>Post a comment</h1>
  <form action="'.$_SERVER['REQUEST_URI'].'" method="post">

      <div class="form-group">
          <label for="exampleFormControlTextarea1" style="margin-top: 7px;margin-bottom: 7px;">Type your
              comment</label>
          <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
          <input type="hidden" name="sno" value="' . $_SESSION['sno']. '">
      </div>
      <button type="submit" class="btn btn-success my-2 ">Post Comment</button>
  </form>
</div>';
}
else {
    echo '<div class="container">
            <h1>Post a comment</h1>
            <p class="lead">You are not logged in. Please login to be able to comment</p>
            </div>';
}
?>
    <div class="container mb-5">
        <h1 style="padding: 5px;">Discussions</h1>
        <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `comments` WHERE `thread_id` = $id";
        $result = mysqli_query($conn,$sql);
        $noresult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noresult = false;
            $id = $row['comment_id'];
            $content = $row['comment_content'];
            $comment_time = $row['comment_time'];
            $thread_user_id = $row['comment_by'];
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($result2);
   
           echo '<div class="d-flex align-items-center my-4">
                <div class="flex-shrink-0">
                    <img src="images/userdefault.png" width="60px" alt="...">
                </div>
                <div class="flex-grow-1 ms-3 m">
                    <p class="font-weight-bold my-0">' .$row2['user_email'] .' at ' .$comment_time. '</p>
                    
                    '.$content.'
                </div>
            </div>';
        }

        if ($noresult) {
            echo'<div class="jumbotron jumbotron-fluid">
            <div class="container">
            <p class="display-6" style="margin-left: 12px;">No Comments Found</p>
            <p class="lead" style="margin-left: 12px;"> Be the first person to comment.</p>
        </div>
    </div>';
        }

        // <p class="font-weight-bold my-0">Anonymous user at ' .$comment_time. '</p>
        // <div class="flex-grow-1 ms-3 m">
        //         <p class="font-weight-bold my-0">Anonymous user at' .$comment_time. '</p
        //             '.$content.'
        //         </div>
        //     </div>';
        ?>
    </div>

    <!-- footer -->
    <?php include 'partials/_footer.php'?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>