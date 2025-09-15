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

//Selecting all Airport names
$sql = "SELECT Name, Code FROM airports";

//Sending th query
$result = $conn->query($sql);

if($result->num_rows > 0 )
{
    while($row = $result->fetch_assoc())
    {
      $Airports[] = $row["Name"];
      $Code[] = $row["Code"];
    }

    $Airport1 = $Airports[0];
    $Airport2 = $Airports[1];
    $Airport3 = $Airports[2];
    $Airport4 = $Airports[3];
    $Airport5 = $Airports[4];
    $Airport6 = $Airports[5];

    $Code1 = $Code[0];
    $Code2 = $Code[1];
    $Code3 = $Code[2];
    $Code4 = $Code[3];
    $Code5 = $Code[4];
    $Code6 = $Code[5];

}

session_start();

//If the user that has not logged in yet, attempts to access the Logged Home then it will direct him to Home

if(!isset($_SESSION["CorrectUsername"]) || !isset($_SESSION["CorrectPassword"]))
{
    header("Location:Home.php");
}


?>

<script>

//If departure dropdown input contains an airport, it should disappear form the arrival dropdown menu, and vise versa

function SelectD()
{

    document.getElementById("A1").removeAttribute("hidden");
    document.getElementById("A2").removeAttribute("hidden");
    document.getElementById("A3").removeAttribute("hidden");
    document.getElementById("A4").removeAttribute("hidden");
    document.getElementById("A5").removeAttribute("hidden");
    document.getElementById("A6").removeAttribute("hidden");

    let Airport = document.getElementById("DepartureInput").value;

    for(let i = 1; i<=6; i++)
    {
        let option = document.getElementById("A"+ i).value;

        if(option === Airport)
        {
            document.getElementById("A"+ i).setAttribute("hidden",true);
        }
    }

}

function SelectA()
{

    document.getElementById("D1").removeAttribute("hidden");
    document.getElementById("D2").removeAttribute("hidden");
    document.getElementById("D3").removeAttribute("hidden");
    document.getElementById("D4").removeAttribute("hidden");
    document.getElementById("D5").removeAttribute("hidden");
    document.getElementById("D6").removeAttribute("hidden");

    let Airport = document.getElementById("ArrivalInput").value;

    for(let i = 1; i<=6; i++)
    {
        let option = document.getElementById("D"+ i).value;

        if(option === Airport)
        {
            document.getElementById("D"+ i).setAttribute("hidden",true);
        }
    }

}


</script>

<!DOCTYPE html>
<html lang="en">

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Home.css?v=<?php echo time(); ?>">
</head>

<body>

    <Bar>
        <p> HOME </p>

        <button type="button" onclick='window.location.href="LogOut.php"'> Logout </button>
        <button style="width:180px;" type="button" onclick='window.location.href="My Trips.php"'> See your Booked Trips </button>

    </Bar>

    <FormContent>

        <Form action="Book Flight.php" method="POST" class = "Section" >

            <DepAr>
    
                <block>
    
                    <label> DEPART FROM </label>
                    <select class = "DepartInput" name = "DepartureInput" id = "DepartureInput" onchange="SelectD()" required>
                        <option value="" ></option>
                        <option value="<?php echo $Airport1?>" id="D1"> <?php echo $Airport1." (".$Code1.")" ?> </option>
                        <option value="<?php echo $Airport2?>" id="D2"> <?php echo $Airport2." (".$Code2.")" ?> </option>
                        <option value="<?php echo $Airport3?>" id="D3"> <?php echo $Airport3." (".$Code3.")" ?> </option>
                        <option value="<?php echo $Airport4?>" id="D4"> <?php echo $Airport4." (".$Code4.")" ?> </option>
                        <option value="<?php echo $Airport5?>" id="D5"> <?php echo $Airport5." (".$Code5.")" ?> </option>
                        <option value="<?php echo $Airport6?>" id="D6"> <?php echo $Airport6." (".$Code6.")" ?> </option>
                    </select>
    
                </block>
    
                <block>
    
                    <label> ARRIVE AT </label>
                    <select class = "ArrivlInput" name = "ArrivalInput" id = "ArrivalInput" onchange="SelectA()" required>
                    
                        <option value="" ></option>  
                        <option value="<?php echo $Airport1?>" id="A1"> <?php echo $Airport1." (".$Code1.")" ?> </option>
                        <option value="<?php echo $Airport2?>" id="A2"> <?php echo $Airport2." (".$Code2.")" ?> </option>
                        <option value="<?php echo $Airport3?>" id="A3"> <?php echo $Airport3." (".$Code3.")" ?> </option>
                        <option value="<?php echo $Airport4?>" id="A4"> <?php echo $Airport4." (".$Code4.")" ?> </option>
                        <option value="<?php echo $Airport5?>" id="A5"> <?php echo $Airport5." (".$Code5.")" ?> </option>
                        <option value="<?php echo $Airport6?>" id="A6"> <?php echo $Airport6." (".$Code6.")" ?> </option>
                
                    </select>
    
                </block>
    
            </DepAr>
    
            <DatPas>
    
                <block>
    
                    <label> DATE OF DEPARTURE </label>
                    <input type = "date" class = "DateInput" min="<?php echo date("Y-m-d"); ?>" name = "DateInput" id = "DateInput" required/>
    
                </block>
    
                <block>
    
                    <label> PASSENGERS </label>
                    <input class = "PassengerNo" name = "PassengerNo" id = "PassengerNo" type = "number" min="1" required/>
                
                </block>
    
            </DatPas>
    
            <button type="submit" class = "submit" > FIND TICKETS </button>
    
        </Form>
    
        <SectionTitle>
    
            <titleAirDS class = "Title" > TRAVEL WITH AIR DS </titleAirDS> <br>
            <Quote class = "Quote"> BOOK YOUR TICKETS NOW </Quote>
    
        </SectionTitle>

    </FormContent>

    <footer>

    <p> Contact Number : 210 3530000 <br><br> Contact Email : Airdsairlines@gmail.com</p>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12586.198840455869!2d23.937224695673063!3d37.9409487204273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14a1901ad9e75c61%3A0x38b215df0aeeb3aa!2zzpTOuc61zrjOvc6uz4IgzpHOtc-Bzr_Ou865zrzOrc69zrHPgiDOkc64zrfOvc-Ozr0gzpXOu861z4XOuM6tz4HOuc6_z4IgzpLOtc69zrnOts6tzrvOv8-C!5e0!3m2!1sel!2sgr!4v1747322533807!5m2!1sel!2sgr" width="160" height="160" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade" style="border:solid; border-width: 2px; border-color:rgb(92, 159, 217); border-radius:10px;"></iframe>
    
    </footer>

</body>

</html>