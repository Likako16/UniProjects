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
    $Name = $_POST['Name'];
    $Surname = $_POST['Surname'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Email = $_POST['Email'];

    //Checking if Username exists
    $sql = " SELECT * FROM users WHERE Username ='$Username'";
    $result = $conn->query($sql);
        
    if ($result->num_rows == 0 )
    {
        //Checking if Email exists
        $sql = " SELECT * FROM users WHERE Email ='$Email'";
        $result2 = $conn->query($sql);
        
        if ($result2->num_rows == 0 )
        {

            //Inserting the values
            $sql = "INSERT INTO users (Name, Surname, Username, Password, Email) VALUES ('$Name' ,'$Surname' ,'$Username' ,'$Password' ,'$Email')";
            
            if ($conn->query($sql) === True)
            {
                header("Location: Login.php");
            }
           
        }
        else
        {
            $Error= "Email already used";
        }
    }
    else
    {
        $Error= "Username already exists";
    }
    
}
?>

<script>

    //Validates the Name input (So that it only contains characters)

    function ValidatorN()
    {
        let input = document.getElementById("Name").value;
        const words = input.split(" ");

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ]+$/))
            {
                document.getElementById("Name").style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("Name").style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("Name").style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }


    //Validates the Surname input (So that it only contains characters)

    function ValidatorS()
    {
        let input = document.getElementById("Surname").value;
        const words = input.split(" ");

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ]+$/))
            {
                document.getElementById("Surname").style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("Surname").style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("Surname").style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

    //Validates the Username input (So that it only contains characters)

    function ValidatorU()
    {
        let input = document.getElementById("Username").value;
        const words = input.split(" ");

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ0-9]+$/))
            {
                document.getElementById("Username").style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("Username").style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("Username").style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

    //Validates the Password input (So that it contains atleast one Number and characters, also checks length)

    function ValidatorP()
    {
        let input = document.getElementById("Password").value;
        const words = input.split(" ");

        Valid="no";

        let HowMany = (input.match(/[0-9]/g) || []).length;

        if(HowMany>=1)
        {
            Valid="yes";
        }

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ0-9]+$/) && word1.length >= 4 && word1.length <= 10 && Valid==="yes")
            {
                document.getElementById("Password").style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("Password").style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("Password").style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

    //Validates the Email input (So that it only contains characters and numbers plus the '@' symbol)

    function ValidatorE()
    {
        let input = document.getElementById("Email").value;
        const words = input.split(" ");

        Valid="no";

        let HowMany = (input.match(/@/g) || []).length;

        if(HowMany===1)
        {
            Valid="yes";
        }

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ0-9@.]+$/) && Valid==="yes")
            {
                document.getElementById("Email").style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("Email").style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("Email").style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

</script>

<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Register.css?v=<?php echo time();?>"> <!-- echo time() is used so that it will update te css file for every change that i make -->
</head>

<body class = "RootBackground">
  
    <Bar>
        <titlle>AIR</titlle><p>DS</p>
    </Bar>

    <form class = "Form" action="Register.php" method="POST" class = "Form">

        <Block>
            <button type = "button" onclick='window.location.href="Login.php"' class = "GoBack"> Go back to Login </button>

            <?php 
            if(!empty($Error))
            {
                echo "<div> $Error </div>";
            }
            ?>

        </Block>
        
        <ttitle>REGISTER</ttitle>

        <Block>

            <block>
                <!-- Name input -->
                <label> Name </label>
                <input class = "NameInput" name = "Name" id = "Name" oninput="ValidatorN()" value="<?php echo !empty($Name) ? $Name : '' ?>" required></input>
    
            </block>
    
            <block>
                <!-- Surname input -->
                <label> Surname </label>
                <input class = "SurnameInput" name = "Surname" id = "Surname" oninput="ValidatorS()" value="<?php echo !empty($Surname) ? $Surname : '' ?>" required></input>
    
            </block>

        </Block>

        <Block>

            <block>
                <!-- Username input -->
                <label> Username </label>
                <input class = "UsernameInput" name = "Username" id = "Username" oninput="ValidatorU()" value="<?php echo !empty($Username) ? $Username : '' ?>" required ></input>
    
            </block>
    
            <block>
                <!-- Password input -->
                <label> Password </label>
                <input class = "PasswordInput" type="password" name = "Password" id = "Password" oninput="ValidatorP()" value="<?php echo !empty($Password) ? $Password : '' ?>"required ></input>
    
            </block>

        </Block>

        <Block>

            <block>
                <!-- Email input -->
                <label> Email </label>
                <input class = "EmailInput" name = "Email" id = "Email" oninput="ValidatorE()"  value="<?php echo !empty($Email) ? $Email: '' ?>" required></input>
    
            </block>

        </Block>

        <button type="submit" name = "Submit" id = "Submit" > Register </button>

    </form>

    <footer>

        <p> Contact Number : 210 3530000 <br><br> Contact Email : Airdsairlines@gmail.com</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12586.198840455869!2d23.937224695673063!3d37.9409487204273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14a1901ad9e75c61%3A0x38b215df0aeeb3aa!2zzpTOuc61zrjOvc6uz4IgzpHOtc-Bzr_Ou865zrzOrc69zrHPgiDOkc64zrfOvc-Ozr0gzpXOu861z4XOuM6tz4HOuc6_z4IgzpLOtc69zrnOts6tzrvOv8-C!5e0!3m2!1sel!2sgr!4v1747322533807!5m2!1sel!2sgr" width="160" height="160" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade" style="border:solid; border-width: 2px; border-color:rgb(92, 159, 217); border-radius:10px;"></iframe>
    
    </footer>


</body>
</html>