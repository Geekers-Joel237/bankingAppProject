<?php

//Create connection
$conn = mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME);

//Check the connection
if (mysqli_connect_errno()) {
    //connection failed
    echo 'Failed to connect to MySql' . mysqli_connect_errno();
}

?>