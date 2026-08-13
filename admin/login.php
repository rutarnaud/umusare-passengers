<?php

session_start();

$error = "";


if(isset($_POST['login'])){

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    // Validate fields

    if($username === "" || $password === ""){

        $error = "Please enter username and password.";

    }else{


        // Temporary admin credentials
        // We will move these to database authentication later.

        $adminUsername = "admin";
        $adminPassword = "admin123";


        if(
            hash_equals($adminUsername, $username) &&
            hash_equals($adminPassword, $password)
        ){

            // Prevent session fixation

            session_regenerate_id(true);

            $_SESSION['admin'] = true;

            header("Location: index.php");

            exit();

        }else{

            $error = "Invalid username or password.";

        }

    }

}

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Admin Login | Umusare Passengers
</title>


<style>

body{

    font-family:Arial;

    background:#f5f5f5;

    display:flex;

    justify-content:center;

    align-items:center;

    height:100vh;

    margin:0;

}


.login-box{

    background:white;

    padding:30px;

    width:350px;

    max-width:90%;

    border-radius:10px;

    box-shadow:0 5px 20px rgba(0,0,0,.1);

}


h2,
h3{

    color:#0B1F3A;

}


input{

    width:100%;

    box-sizing:border-box;

    padding:12px;

    margin:10px 0;

    border:1px solid #ddd;

    border-radius:5px;

}


button{

    width:100%;

    padding:12px;

    margin-top:10px;

    background:#0B1F3A;

    color:white;

    border:none;

    border-radius:5px;

    cursor:pointer;

}


button:hover{

    opacity:.9;

}


.error{

    color:#dc3545;

    background:#f8d7da;

    padding:10px;

    border-radius:5px;

    margin-bottom:10px;

}

</style>

</head>


<body>


<div class="login-box">


<h2>
Umusare Passengers
</h2>


<h3>
Admin Login
</h3>


<?php if($error !== ""){ ?>

<div class="error">

<?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<form method="POST">


<input
type="text"
name="username"
placeholder="Username"
autocomplete="username"
required>


<input
type="password"
name="password"
placeholder="Password"
autocomplete="current-password"
required>


<button
type="submit"
name="login">

Login

</button>


</form>


</div>


</body>

</html>