<?php 
    require '../config/config.php';
    require '../config/bd.php';

    session_start(); 

    //Message Vars
    $msg = '';
    $msgClass = '';

    //Check for submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = test_input($_POST['password']);
        $confirm_pw = test_input($_POST['confirm_pw']);

        //Validation

        if (!empty($password) && !empty($confirm_pw)) {
            //Passed
           if ($password != $confirm_pw) {
                $msg = 'Please confirm Your Password';
               $msgClass = 'alert-danger';
            }else {
                //tout est correct
                $password = mysqli_real_escape_string($conn,$password);
 
                
                $query1 = 'SELECT * FROM user WHERE email='. '"'.$_SESSION['usermail'].'"'.'';

                
                //get result
                $result1 = mysqli_query($conn,$query1);

                
                 //fetch data
                $emails = mysqli_fetch_assoc($result1);
                
                   
                //Free result1
               mysqli_free_result($result1);    

                $query = "UPDATE user SET
                    userpassword = '$password'
                WHERE email = '{$_SESSION['usermail']}'";

                

                if (mysqli_query($conn,$query)) {
                    $msg = 'Youpi your have setup your new password';
                    $msgClass = 'alert-success';
                    

                   //close connection
                                        
                 } else {
                   echo 'ERROR' . mysqli_error($conn);
                }
            }
         }
       
         else {
            //Failed
            $msg = 'Please fill in all fields ';
            $msgClass = 'alert-danger';
        }
    
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Set Password Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/main.css">
</head>
<body class="border login border-3">
    <section class="p-5  rounded h-100 ">
        <div class="container h-100 rounded">
            <?php if($msg != '') :?>
                <div class="alert <?php echo $msgClass?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif;?>
            <div class="row h-100 justify-content-between align-items-center g-3">
                <div class="col-md bg-light shadow border">
                    <div class="h1 text-center text-primary my-3 text-uppercase">Set Password</div>
                    <form class="p-3" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
        method="post">
                        <div class="form-group mb-2">
                            <label for="password"> New Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                          </div>
                          <div class="form-group mb-2">
                            <label for="confirm_pw"> Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_pw" id="password" placeholder="Password" required>
                          </div>
                          
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="./login.php" class="alert ms-5"><i class="fa-solid fa-unlock-keyhole"></i>
                              Login</a>
                      </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>