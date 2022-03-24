<?php
  require '../config/config.php';
  require '../config/bd.php';

  session_start();


  //get user info
  $query1 = 'SELECT * FROM user WHERE email='. '"'.$_SESSION['usermail'].'"'.'';
  $result1 = mysqli_query($conn,$query1);
  $user_infos = mysqli_fetch_assoc($result1);
  mysqli_free_result($result1);

  $query1 = 'SELECT * FROM compte WHERE user_EMAIL='. '"'.$_SESSION['usermail'].'"'.'';
  $result1 = mysqli_query($conn,$query1);
  $account_infos = mysqli_fetch_assoc($result1);
  mysqli_free_result($result1);


  $query1 = 'SELECT email FROM user';
  $result1 = mysqli_query($conn,$query1);
  $emails = mysqli_fetch_all($result1,MYSQLI_ASSOC);
  // print_r($emails);
  mysqli_free_result($result1);

  $query1 = 'SELECT * FROM transaction WHERE compte_user_EMAIL='. '"'.$_SESSION['usermail'].'"'.'ORDER BY DATE  DESC';
  $result1 = mysqli_query($conn,$query1);
  $transaction_infos = mysqli_fetch_all($result1,MYSQLI_ASSOC);
  // var_dump($transaction_infos);
  mysqli_free_result($result1);

  //Message Vars
  $msg = '';
  $msgClass = '';
  
//Check for submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sender = test_input($_POST['sender']);
  $password = test_input($_POST['password']);
  $receiver = test_input($_POST['receiver']);
  $amount = (int) test_input($_POST['amount']);
  $motif = test_input($_POST['motif']);

  $query1 = 'SELECT * FROM compte WHERE user_EMAIL='. '"'.$receiver.'"'.'';
  $result1 = mysqli_query($conn,$query1);
  $account_infos2 = mysqli_fetch_assoc($result1);
  mysqli_free_result($result1);

  
  //validation
  if (!empty($receiver) && !empty($motif) && !empty($amount)) {
    //passed
      //test receiver email
      if (filter_var($receiver,FILTER_VALIDATE_EMAIL) === false) {
        //failed
        $msg = 'Please use a valid email';
        $msgClass = 'alert-danger';
    } else {
      //valid receiver email
        //check if reveiver email in bd
        if (in_db($emails,$receiver)) {
          //check si transaction possible
          $actual = (int)$account_infos['AMOUNT'];
          if ( $amount >= $actual) {
            $msg = 'You cannot send more you have';
            $msgClass = 'alert-danger';
          } else {
            //effectuer la transaction
            
            $sender_cash = $actual - $amount;
            // echo 'sender_cash'. $sender_cash . '<br>';
            $receiver_cash = (int)$account_infos2['AMOUNT'] + $amount;
            // echo 'receiver_cash'. $receiver_cash . '<br>';
            $query1 = "UPDATE compte SET
            amount = '$sender_cash'
            WHERE user_EMAIL = '{$_SESSION['usermail']}'";

            $query2 = "UPDATE compte SET
            amount = '$receiver_cash'
            WHERE user_EMAIL = '$receiver'";

            if (mysqli_query($conn,$query1) && mysqli_query($conn,$query2)) {
             //Save the transaction
             $query = 
            "INSERT INTO transaction(compte_IDCOMPTE,compte_user_EMAIL,compte_IDCOMPTE1,compte_user_EMAIL1)
            VALUES ('$account_infos[IDCOMPTE]','$sender','$account_infos2[IDCOMPTE]','$receiver')";
            // die($query);
            mysqli_query($conn,$query);
             
              $msg = 'Transaction Success';
              $msgClass = 'alert-success';
              header('Location:userdashboard.php');
              // mysqli_close($conn);
            } else {
              $msg = 'Transaction Denied';
              $msgClass = 'alert-danger';
            }
            

          }
          
        } else {
          $msg = 'This account is unknow';
          $msgClass = 'alert-danger';
        }
    }
  } else {
    //Failed
    $msg = 'Please fill in all fields ';
    $msgClass = 'alert-danger';
  }
}

function in_db($emails,$receiver){
  // var_dump($emails);
  foreach($emails as $email){
    if ($receiver == $email['email']) {
      echo $email['email'];
      return true;
    }
  }
  return false;
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>

    <nav class="navbar bg-primary navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Smart Payment</a>
          <!-- <a href="#" class="navbar-brand">
            <img src="./img/logo.svg" alt="" width="30" height="30">
            Smart Payment</a> -->
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <!-- <li><a href="pages.html">Pages</a></li>
            <li><a href="posts.html">Posts</a></li>
            <li><a href="users.html">Users</a></li> -->
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="nav-ittem"><a href="#" class="nav-link"><span class=""><?php echo 'Welcome ,'?>
                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?></span></a></li>
            <li class="nav-ittem"><a href="#" class="nav-link"><img src="" alt="avatar" srcset="" class="avatar"></a></li>
            <li class="nav-ittem"><a href="login.php" class="nav-link">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <header id="header">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <h1><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Dashboard <small>Manage Your Account</small></h1>
          </div>
          <div class="col-md-2">
            <div class="dropdown create">
              <button class="btn bg-primary text-light btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                New Transaction
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a type="button" data-toggle="modal" data-target="#addPage">Send Money</a></li>
                <!-- <li><a href="#">Add Post</a></li>
                <li><a href="#">Add User</a></li> -->
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section id="breadcrumb">
      <div class="container">
        <ol class="breadcrumb">
          <li class="active">Dashboard</li>
        </ol>
      </div>
    </section>

    <section id="main">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="list-group">
              <a href="index.php" class="list-group-item active dash">
                <span class="glyphicon glyphicon-user bg-primary" aria-hidden="true"></span> Dashboard
              </a>
              <!-- <a href="pages.html" class="list-group-item"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Pages <span class="badge">12</span></a> -->
              <a href="#" class="list-group-item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Transactions <span class="badge"><?php echo count($transaction_infos)?></span></a>
              <a href="settings.php" class="list-group-item"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings</a>
            </div>

            <div class="well">
              <h4>Disk Space Used</h4>
              <div class="progress">
                  <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                      60%
              </div>
            </div>
            <h4>Bandwidth Used </h4>
            <div class="progress">
                <div class="progress-bar bg-primary role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                    40%
            </div>
          </div>
            </div>
          </div>
          <div class="col-md-9">
            <!-- Website Overview -->
            <div class="panel panel-default">
              <div class="panel-heading bg-primary">
                <h3 class="panel-title ">Account Administration Overview</h3>
              </div>
              <div class="panel-body">
                <div class="col-md-6">
                  <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-euro" aria-hidden="true"></span><?php echo $account_infos['AMOUNT']?></h2>
                    <h4>Amount</h4>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> <?php echo count($transaction_infos)?></h2>
                    <h4>Transactions</h4>
                  </div>
                </div>


              <!-- Latest Transactions -->
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Latest Transactions</h3>
                </div>
                <div class="panel-body">
                  <table class="table table-striped table-hover">
                      <tr>
                        <th>EMAIL_ACCOUNT1</th>
                        <th>EMAIL_ACCOUNT2</th>
                        <th>DATE</th>
                        <th>ID_TRANSACTION</th>
                      </tr>
                      
                      <?php foreach ($transaction_infos as $transaction) :?>
                      <tr>
                        <td><?php echo $transaction['compte_user_EMAIL'] ?></td>
                        <td><?php echo $transaction['compte_user_EMAIL1'] ?></td>
                        <td><?php echo $transaction['DATE'] ?></td>
                        <td><?php echo $transaction['IDTRANSACTION'] ?></td>
                      </tr>
                      <?php endforeach;?>
                      
                    </table>
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>

    <footer id="footer">
      <p>Copyright Smart Payment, &copy; 2022</p>
    </footer>

    <!-- Modals -->

    <!-- Add Page -->
    <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
        method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Cash </h4>
        <div class="alert <?php echo $msgClass?>"><?php echo $msg?></div>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Sender Email</label>
          <input type="email" class="form-control" name="sender"
          value="<?php echo $user_infos['EMAIL'] ;?>" placeholder="xxx@zzz.com">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" name="password"
          value="<?php echo $user_infos['USERPASSWORD'] ;?>">
        </div>
        <div class="form-group">
          <label>Receiver Email</label>
          <input type="email" class="form-control" name="receiver" 
          placeholder="xxx@zzz.com" value="<?php echo isset($_POST['receiver']) ? $receiver : '' ?>">
        </div>
        <div class="form-group">
          <label>Amount</label>
          <input type="number" class="form-control" name="amount" min="1" 
          placeholder="xxxFCFA" value="<?php echo isset($_POST['amount']) ? $amount : '' ?>">
        </div>
        <div class="form-group">
          <label>Motif</label>
          <input type="text" class="form-control" name="motif" 
          placeholder="Motif de la transaction" value="<?php echo isset($_POST['motif']) ? $motif : '' ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" name="send" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </form>
    </div>
  </div>
</div>

  <!-- <script>
     CKEDITOR.replace( 'editor1' );
 </script> -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
