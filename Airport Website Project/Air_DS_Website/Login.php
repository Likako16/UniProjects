<?php

//Naming all the needed parameters
$server = "localhost";
$username = "root";
$password = "";
$dbname = "air_ds";

//Connecting
$conn = new mysqli($server, $username, $password, $dbname);

//Checking if connected successfully
if($conn->connect_error)
{
    die("Connection failed: " . $con->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
   
    //Inserting the values
    $sql = " SELECT * FROM users WHERE Username ='$Username' AND Password ='$Password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0 )
    {
        session_start();

        $_SESSION["CorrectUsername"] = $Username;
        $_SESSION["CorrectPassword"] = $Password;
        
        header("Location:LoggedHome.php");
    }
    else
    {
        $Error = "Username or Password is incorrect ";
    }
}

/*$AccountCreated = "Account Made successfully";*/

?>

<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Login.css?v=<?php echo time(); ?>">
</head>

<body class = "RootBackground">
  
    <Bar>
        <titlle>AIR</titlle><p>DS</p>
    </Bar>

    <form action="Login.php" method="POST" class = "Form">
        
        <?php 
        if(!empty($AccountCreated))
        {
            echo "<div style='background-color:rgb(216, 240, 216);border-color: rgb(165, 219, 174); color: rgb(165, 214, 175); border: solid;border-width: 1px;border-radius: 10px;'> $AccountCreated </div>";
        }
        else if(!empty($Error))
        {
            echo "<div style='margin-top:-50px;background-color:rgb(255, 219, 219);border-color: rgb(228, 189, 189); color: rgb(212, 174, 174);border: solid;border-width: 1px;border-radius: 10px;'> $Error </div>";
        }
        else
        {
            echo "<div></div>";
        }
        ?>

        <ttitle>LOGIN</ttitle>

        <block>

            <label> Username </label>
            <input class = "UsernameInput" name = "Username" id = "Username" required></input>

        </block>

        <block>

            <label> Password </label>
            <input class = "PasswordInput" type="password" name = "Password" id = "Password" required></input>

        </block>

        <button type="submit" name = "Submit" id = "Submit" > Login </button>

        <Tip> If you dont own an account<br> Register <a href="Register.php">here<a> </Tip>

    </form>

    <footer>

        <p> Contact Number : 210 3530000 <br><br> Contact Email : Airdsairlines@gmail.com</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12586.198840455869!2d23.937224695673063!3d37.9409487204273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14a1901ad9e75c61%3A0x38b215df0aeeb3aa!2zzpTOuc61zrjOvc6uz4IgzpHOtc-Bzr_Ou865zrzOrc69zrHPgiDOkc64zrfOvc-Ozr0gzpXOu861z4XOuM6tz4HOuc6_z4IgzpLOtc69zrnOts6tzrvOv8-C!5e0!3m2!1sel!2sgr!4v1747322533807!5m2!1sel!2sgr" width="160" height="160" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade" style="border:solid; border-width: 2px; border-color:rgb(92, 159, 217); border-radius:10px;"></iframe>
    
    </footer>

</body>
</html>