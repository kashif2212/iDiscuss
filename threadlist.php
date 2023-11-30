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
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `categories` WHERE category_id = $id";
        $result = mysqli_query($conn,$sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $catname = $row['category_name'];
            $catdesc = $row['category_description'];
        }
    ?>
    <?php
    $showAlert = false;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            //Insert thread into database
            $th_title = $_POST['thread_title'];//Inside square bracket thread_title is the name given in the form in line number 64
            $th_desc = $_POST['thread_desc'];//Inside square bracket thread_desc is the name given in the form in line number 64

            $th_title = str_replace("<" , "&lt;" , $th_title);
            $th_title = str_replace(">" , "&gt;" , $th_title);

            $th_desc = str_replace("<" , "&lt;" , $th_desc);
            $th_desc = str_replace(">" , "&gt;" , $th_desc);

            $sno = $_POST['sno'];
            $sql = "INSERT INTO `thread` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
            $result = mysqli_query($conn,$sql);
            $showAlert = true;
            if ($showAlert) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your thread has been added! Please wait for community to respond
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>';
            }
        }
    ?>
    <div class="container">
        <div class="jumbotron text-dark my-5" style="background-color:rgb(199, 199, 199); padding:50px;">
            <h1 class="display-4 text-center" style="font-weight:bold; margin-bottom: 17px; padding-bottom:12px;">
                Welcome to <?php echo $catname ;?> forums</h1>
            <p class="lead text-dark" style="font-size:24px; font-weight:normal;"><?php echo $catdesc ;?></p>
            <hr class="my-4">
            <p style="font-size:18px;">No Spam / Advertising / Self-promote in the forums is not allowed. Do not post
                copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post
                questions. Remain respectful of other members at all times.</p>
            <a href="#" class="btn btn-success btn-lg" role="button">Learn More</a>
        </div>
    </div>
 <?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
  echo  '<div class="container">
        <h1>Start a discussion</h1>
        <form action="'.$_SERVER["REQUEST_URI"].'"method="post">
            <div class="form-group" style="margin-top: 12px;margin-bottom: 12px;">
                <label for="exampleInputTitle" style="margin-top: 7px;margin-bottom: 7px;">Problem Title</label>
                <input type="text" class="form-control" name="thread_title" id="title" aria-describedby="emailHelp"
                    placeholder="title">
                <small id="titleHelp" class="form-text text-muted">Keep Your title as crisp as possible.</small>
            </div>
            <input type="hidden" name="sno" value="' . $_SESSION['sno']. '">
            <div class="form-group">
                <label for="exampleFormControlTextarea1" style="margin-top: 7px;margin-bottom: 7px;">Elaborate your problem </label>
                <textarea class="form-control" id="desc" name="thread_desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success my-2 ">Submit</button>
        </form>
    </div>';
}
else {
    echo '<div class="container">
            <h1>Start a discussion</h1>
            <p class="lead">You are not logged in. Please login to start a discussion</p>
            </div>';
}
?>
    <div class="container mb-5" id="ques">
        <h1 style="padding-top: 5px;padding-bottom: 5px;margin-top: 15px;">Browse Questions</h1>
        <hr>
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `thread` WHERE `thread_cat_id` = $id";
        $result = mysqli_query($conn,$sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $thread_id = $row['thread_id'];
            $thread_title = $row['thread_title'];
            $thread_desc = $row['thread_desc'];
            $thread_time = $row['timestamp'];

            $thread_user_id = $row['thread_user_id'];
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $me= $row2['user_email'];
            
   
           echo '<div class="d-flex align-items-center my-4">
                <div class="flex-shrink-0">
                    <img src="images/userdefault.png" width="60px" alt="...">
                </div>
                <div class="flex-grow-1 ms-3 m">
                <p class = "font-weight-bold my-0">'. $me .' at ' .$thread_time . '</p>    
                <h5 class="mt-0 "><a class= "text-dark" href="thread.php?threadid='. $thread_id . '">'. $thread_title .'</a></h5>
                    '.$thread_desc.'
                </div>
            </div>';
        
        }
        // echo var_dump($noResult);
        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
                        <div class="container">
                        <p class="display-6" style="margin-left: 12px;">No Threads Found</p>
                        <p class="lead" style="margin-left: 12px;"> Be the first person to ask the question.</p>
                    </div>
                </div>';
            
          
        } 
        ?>
    </div>

    <!-- footer -->
    <?php include 'partials/_footer.php'?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
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