<?php 
    require '../config/config.php';
    require '../config/bd.php';

    //Message Vars
    $msg = '';
    $msgClass = '';

    //Check for submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);

        //Validation

        if ( !empty($email)&& !empty($password)) {
            //Passed
                
                //test email
                if (filter_var($email,FILTER_VALIDATE_EMAIL) === false) {
                    //failed
                    $msg = 'Please use a valid email';
                    $msgClass = 'alert-danger';
                } else {
                    //passed

                            $email = mysqli_real_escape_string($conn,$email);
                            $password = mysqli_real_escape_string($conn,$password);

                        //     //create query
                            $query1 = 'SELECT * FROM user';

                        //     //get result
                            $result1 = mysqli_query($conn,$query1);

                        //     //fetch data
                            $emails = mysqli_fetch_all($result1,MYSQLI_ASSOC);

                        //     //Free result1
                            mysqli_free_result($result1);

                            //close connection
                            // mysqli_close($conn);

                            foreach ($emails as $mail) {
                              
                              if ($email == $mail[strtoupper('email')] && $password == $mail[strtoupper('userpassword')]) {
                                session_start();  
                                $_SESSION['username'] = $mail['NOM'];
                                header('Location:index.php');
                                break;
                                        
                                } else {
                                  $msg = 'Incorrect email or password';
                                  $msgClass = 'alert-danger';
                                }
                            }
                       
                }
                
        } else {
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
    <title>Login Page</title>
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
                <div class="col-lg-6 col-md d-none d-md-block">
                    <img src="../dist/img/login.svg" alt="" class="img-fluid">
                </div>
                <div class="col-lg-6 col-md shadow bg-light border">
                    <div class="h1 text-center text-primary my-3">LOGIN</div>
                    <form class="p-3" method ="post" 
                    action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                        <div class="form-group mb-2">
                          <label for="email">Email address</label>
                          <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                      
                        </div>
                        <div class="form-group mb-2">
                          <label for="password">Password</label>
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                        <div class="form-check mb-2">
                          <input type="checkbox" class="form-check-input" id="exampleCheck1">
                          <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <a href="./forgetpw.php" class="alert ms-5"><i class="fa-solid fa-unlock-keyhole"></i>
                              Forget My Password</a>
                          <div class="form-group my-2">
                            <p>You are new , Dont worry <a href="./register.php" class="alert"> Sign Up</a></p>
                          </div>
                        
                      </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>