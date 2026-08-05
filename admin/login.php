<?php

session_start();


$error = "";


if(isset($_POST['login'])){


$username = $_POST['username'];

$password = $_POST['password'];



if($username == "admin" && $password == "admin123"){


$_SESSION['admin'] = true;


header("Location: index.php");

exit();


}else{


$error = "Invalid username or password";


}


}

?>


<!DOCTYPE html>
<html>

<head>

<title>Admin Login | Umusare Passengers</title>


<style>

body{

font-family:Arial;
background:#f5f5f5;
display:flex;
justify-content:center;
align-items:center;
height:100vh;

}


.login-box{

background:white;
padding:30px;
width:350px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,.1);

}


input{

width:100%;
padding:12px;
margin:10px 0;

}


button{

width:100%;
padding:12px;
background:#0B1F3A;
color:white;
border:none;

}


.error{

color:red;

}

</style>

</head>


<body>


<div class="login-box">


<h2>Umusare Passengers</h2>

<h3>Admin Login</h3>


<?php echo $error; ?>


<form method="POST">


<input type="text" name="username" placeholder="Username">


<input type="password" name="password" placeholder="Password">


<button name="login">
Login
</button>


</form>


</div>


</body>

</html>