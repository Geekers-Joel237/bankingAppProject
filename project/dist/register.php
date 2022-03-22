<?php 
    require '../config/config.php';
    require '../config/bd.php';

    //Message Vars
    $msg = '';
    $msgClass = '';

    //Check for submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = test_input($_POST['name']);
        $surname = test_input($_POST['surname']);
        $telephone = test_input($_POST['telephone']);
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        $confirm_pw = test_input($_POST['confirm_pw']);

        //Validation

        if (!empty($name) && !empty($surname) && !empty($telephone) 
        && !empty($email)&& !empty($password) && !empty($confirm_pw)) {
            //Passed
                //test name
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $msg = "Only letters and white space allowed";
                    $msgClass = 'alert-danger';
                }
                //test email
                if (filter_var($email,FILTER_VALIDATE_EMAIL) === false) {
                    //failed
                    $msg = 'Please use a valid email';
                    $msgClass = 'alert-danger';
                } else {
                    //passed
                        //test password
                        if ($password != $confirm_pw) {
                            $msg = 'Please confirm Your Password';
                            $msgClass = 'alert-danger';
                        }else {
                             //tout est correct

                            $name = mysqli_real_escape_string($conn,$name);
                            $surname = mysqli_real_escape_string($conn,$surname);
                            $telephone = mysqli_real_escape_string($conn,$telephone);
                            $email = mysqli_real_escape_string($conn,$email);
                            $password = mysqli_real_escape_string($conn,$password);

                            //create query
                            $query1 = 'SELECT email FROM user';

                            //get result
                            $result1 = mysqli_query($conn,$query1);
                            
                            //fetch data
                            $emails = mysqli_fetch_all($result1,MYSQLI_ASSOC);
                            
                            //Free result1
                            mysqli_free_result($result1);

                            if (count($emails) == 0) {
                                //new user
                                $query = "INSERT INTO user(email,nom,prenom,userpassword,tel) 
                                VALUES ('$email','$name','$surname','$password','$telephone')";
                                addUser($conn,$query,$msg,$msgClass);
                            } else {

                                foreach ($emails as $mail) {
                                
                                    if ($email == $mail['email']) {
                                        //old user
                                        $msg = 'User Already in Database';
                                        $msgClass = 'alert-danger';
                                    } 
                                    else {
                                        //new user
                                        $query = "INSERT INTO user(email,nom,prenom,userpassword,tel) 
                                        VALUES ('$email','$name','$surname','$password','$telephone')";
                                        addUser($conn,$query,$msg,$msgClass);
                                    }
                                }
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

    function addUser($conn,$query,&$msg,&$msgClass) {
        //new user
       
        if (mysqli_query($conn,$query)) {
            $msg = 'Youpi your are register';
            $msgClass = 'alert-success';
           
             //close connection
            
        } else {
            // echo 'ERROR' . mysqli_error($conn);
        }
    }
?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Register Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/main.css">

</head>
<body  class="login">
    <section class="p-5  rounded h-100 ">
        <div class="container h-100 rounded">
            <?php if($msg != '') :?>
                <div class="alert <?php echo $msgClass?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif;?>
            <div class="row h-100 justify-content-between align-items-center g-4">
                <div class="col-lg-6 col-md d-none d-md-block order-2">
                    <img src="../dist/img/register.svg" alt="" class="img-fluid">
                </div>
                <div class="col-lg-6 col-md bg-light shadow border">
                    <div class="h1 text-center text-primary my-3 text-uppercase">Register</div>
                    <form class="p-3" method ="post" 
                    action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                        <div class="form-group mb-2">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" name="name" id="name" 
                          value="<?php echo isset($_POST['name']) ? $name : '' ;?>" placeholder="Name" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" name="surname" id="surname" 
                            value="<?php echo isset($_POST['surname']) ? $surname : '' ;?>" placeholder="Surname" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="tel">Telephone</label>
                            <input type="text" class="form-control" name="telephone" id="tel" 
                            value="<?php echo isset($_POST['telephone']) ? $telephone : '' ?>" placeholder="6xxxxxxxx" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" 
                            value="<?php echo isset($_POST['email']) ? $email : '' ?>" placeholder="Enter email" required>
                
                          </div>
                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                            value="<?php echo isset($_POST['password']) ? $password : '' ?>" placeholder="Password" required>
                        </div>
                       <div class="form-group mb-2">
                            <label for="confirm">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_pw" id="confirm" 
                            value="<?php echo isset($_POST['confirm_pw']) ? $confirm_pw : '' ?>" placeholder="Password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary my-2">Submit</button>
                        <a href="./login.php" class="alert ms-5"><i class="fa-solid fa-arrow-right-to-bracket"></i>
                            Already have an account</a>
                      </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>