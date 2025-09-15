<?php

session_start();

//If the user has not logged in, it will redirect him to the login page 

if(!isset($_SESSION["CorrectUsername"]) || !isset($_SESSION["CorrectPassword"]))
{
    header("Location:Login.php");
}

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

if(($_SERVER["REQUEST_METHOD"] == "POST"))
{
    $Departure = $_POST['DepartureInput'];

    if( trim($Departure) == "Athens International Airport")
    {
        $Departure = 'Athens International Airport "Eleftherios Venizelos"'; // This happens because the htm, when recieving port variables, cannot recognize anything inside "". So it deletes "Eleftherios Venizelos". I make sure that the name stays as it is
    }

    $Arrival = $_POST['ArrivalInput'];

    if( trim($Arrival) == "Athens International Airport")
    {
        $Arrival = 'Athens International Airport "Eleftherios Venizelos"'; // Same as Departure
    }

    $Date = $_POST['DateInput'];
    $PassengerNo = (int) $_POST['PassengerNo'];

    $Username = $_SESSION['CorrectUsername'];

    //Searching for Name and Username
    $sql = " SELECT * FROM users WHERE Username ='$Username'";
    $result = $conn->query($sql);
        
    if ($result->num_rows > 0 )
    {
        while($row = $result->fetch_assoc())
        {
            $Name = $row["Name"];
            $Surname = $row["Surname"];
        }

    }
    
}

//Lets see which seats are taken for this date

$sql = " SELECT Seat FROM reservations WHERE Date ='$Date' AND Departure ='$Departure' AND Arrival ='$Arrival'";
$result = $conn->query($sql);
        
if ($result->num_rows > 0 )
{
    while ($row = $result->fetch_assoc())
    {
        $Seats[] = $row['Seat'];
    }
}



//Searching the Tax, Latitude and Longtitude
$sql = " SELECT * FROM airports WHERE Name ='$Departure'";
$result = $conn->query($sql);

if ($result->num_rows > 0 )
{
    while($row = $result->fetch_assoc())
    {
        $Tax1 = $row["Tax"];
        $Lat1 = (float)$row["Latitude"];
        $Long1 = (float)$row["Longitude"];
    }

}

$sql = " SELECT * FROM airports WHERE Name ='$Arrival'";
$result = $conn->query($sql);

if ($result->num_rows > 0 )
{

    while($row2 = $result->fetch_assoc())
    {
        $Tax2 = $row2["Tax"];
        $Lat2 = (float)$row2["Latitude"];
        $Long2 = (float)$row2["Longitude"];
    }

}

$Tax = $Tax1 + $Tax2;

//d = 2R × sin⁻¹(√[sin²((θ₂ - θ₁)/2) + cosθ₁ × cosθ₂ × sin²((φ₂ - φ₁)/2)])
$R = 6371.0;

$d = 2*$R * asin( sqrt( pow( sin( ( $Lat2 - $Lat1)/2 ), 2 ) + cos($Lat1)*cos($Lat2)*pow(sin(($Long2 - $Long1)/2 ),2)) );

$FlightCost = $d / 10;

$TicketCost = $Tax + $FlightCost;


?>

<script>

    //Estimates the cost of a selected seat.

    function Estimate(i,seat)
    {
        document.getElementById("FinalCost").value="";

        for(let passenger = 1; passenger <= <?php echo "$PassengerNo" ?>; passenger++)
        {
            if (document.getElementById("P" + passenger).value === "" )
            {
                document.getElementById("P" + passenger).value = seat;
                document.getElementById(seat).style.backgroundColor = " rgb(165, 235, 211)";
                document.getElementById(seat).style.borderColor = " rgb(83, 140, 120)";
                document.getElementById(seat).setAttribute("disabled",true);

                if (i === 1 || i === 11 || i === 12)
                {
                    document.getElementById("CC" + passenger).value = "20€";
                }
                else if ( i === 2 || i === 3 || i === 4 || i === 5 || i === 6 || i === 7 || i === 8 || i === 9 || i === 10 )
                {
                    document.getElementById("CC" + passenger).value = "10€";
                }
                else
                {
                    document.getElementById("CC" + passenger).value = "-";
                }

                break;
            }

        }
    
    }

    // Basically diselects a seat

    function Erase(i)
    {
        let seat = document.getElementById("P" + i).value;
        let cost = document.getElementById("CC" + i).value;
        document.getElementById("P" + i).value = "";
        document.getElementById("CC" + i).value = "";

        document.getElementById("FinalCost").value="";

        document.getElementById(seat).removeAttribute("disabled");

        if (cost === "20€")
        {
            document.getElementById(seat).style.backgroundColor = "rgb(199, 209, 223)";
            document.getElementById(seat).style.borderColor = "rgb(164, 186, 207)";
        }
        else if (cost === "10€")
        {
            document.getElementById(seat).style.backgroundColor = "rgb(133, 152, 182)";
            document.getElementById(seat).style.borderColor = "rgb(164, 186, 207)";
        }
        else
        {
            document.getElementById(seat).style.backgroundColor = "rgb(241, 250, 252)";
            document.getElementById(seat).style.borderColor = "rgb(164, 186, 207)";
        }

    }
    
    //Validating the name inputs

    function ValidatorN(i)
    {
        let input = document.getElementById("PName"+i).value;
        const words = input.split(" ");

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ]+$/) && word1.length >= 3 && word1.length <= 20)
            {
                document.getElementById("PName"+i).style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("PName"+i).style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("PName"+i).style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

    //Validating the surname inputs

    function ValidatorS(i)
    {
        let input = document.getElementById("PSurname"+i).value;
        const words = input.split(" ");

        for (let word1 of words)
        {
            if ( word1.match(/^[a-zA-Zα-ωΑ-ΩάέήίόύώΆΈΉΊΌΎΏ]+$/) && word1.length >= 3 && word1.length <= 20)
            {
                document.getElementById("PSurname"+i).style.backgroundColor = "rgb(194, 233, 219)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else if(word1==="")
            {
                document.getElementById("PSurname"+i).style.backgroundColor = "rgb(255, 255, 255)";
                document.getElementById("Submit").removeAttribute("disabled");
            }
            else
            {
                document.getElementById("PSurname"+i).style.backgroundColor = "rgb(244, 204, 189)";
                document.getElementById("Submit").setAttribute("disabled",true);
            }
        }
    }

    //respansible for what the cost feild will show when the seats are selected

    function Cost()
    {
        TicketCost=<?php echo $TicketCost?>;
        let Costt=0;
        let passenger;
        for(passenger = 1; passenger <= <?php echo "$PassengerNo" ?>; passenger++)
        {
            if(document.getElementById("CC"+ passenger).value != "-")
            {
                Costt+= parseFloat(document.getElementById("CC"+ passenger).value);
            }

        }
              
        TicketCost+=Costt;
        FinalCost=(passenger-1)*TicketCost;

        if (isNaN(FinalCost))
        {
            document.getElementById("FinalCost").value = "Select all seats";
        }
        else
        {
            document.getElementById("FinalCost").value = FinalCost;
        }
    
    }

</script>

<!DOCTYPE html>
<html lang="en">

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Book Flight.css?v=<?php echo time(); ?>">
</head>

<body>

    <Bar>
        <titlle> FLYING WITH AIR</titlle><p>DS</p>      
    </Bar>

    <form action="My Trips.php" method="POST">

        <InformationSection>

        <Titl> Please fill all the required passenger Information</Titl>

                <!-- Those inputs exist only for posting the varaibles to the next .php, when user submits the form -->
                <input type="hidden" name='Departure' id='Departure' value = "<?php echo $Departure ?>" required>
                <input type="hidden" name='Arrival' id='Arrival' value = "<?php echo $Arrival ?>" required>
                <input type="hidden" name='Date' id='Date' value = "<?php echo $Date ?>" required>
                <input type="hidden" name='PassengerNo' id='PassengerNo' value = "<?php echo $PassengerNo ?>" required>

                <Passengers>

                    <num>Passenger 1</num>

                    <passenger>

                        <block>
                            <label> Name </label>
                            <Name> <?php echo $Name ?> </Name>
                        </block>

                        <block>
                            <label> Surname </label>
                            <Surname> <?php echo $Surname ?> </Surname>
                        </block>

                    </passenger>

                    <?php

                        //For each passenger it show another feild for name and surname input
                        for ( $i = 2; $i <= $PassengerNo; $i++ )
                        {
                            echo "<num>Passenger $i</num>";

                            echo "<passenger>";
                                echo "<block>";
                                    echo "<label> Name </label>";
                                    echo "<Input name='PName$i' id='PName$i' oninput='ValidatorN($i)' required></Input>";
                                echo "</block>";
                                echo "<block>";
                                    echo "<label> Surname </label>";
                                    echo "<Input name='PSurname$i' id='PSurname$i' oninput='ValidatorS($i)' required></Input>";
                                echo "</block>";
                            echo "</passenger>";
                        }

                    ?>

                </Passengers>
                
            </InformationSection>

            <seatSelection>

            <Titl> Please Select the seats for the date : <?php echo $Date ?></Titl>

                <Plane>
                    <seatId>
                        <Row>ABC</Row>
                        <Row>DEF</Row>
                    </seatId>

                    <?php

                        //this outputs all the seats for the plane. The loop is for each row

                        for ( $i = 1; $i <= 31; $i++ )
                        {
                            echo"<Row>";
                                echo"<Colu>";
                                    echo"<button type='button' name='A$i' id='A$i' onclick='Estimate($i,\"A$i\")'></button>";
                                    echo"<button type='button' name='B$i' id='B$i' onclick='Estimate($i,\"B$i\")'></button>";
                                    echo"<button type='button' name='C$i' id='C$i' onclick='Estimate($i,\"C$i\")'></button>";
                                echo"</Colu>";
                                    echo"<p>$i</p>";
                                echo"<Colu>";
                                    echo"<button type='button' name='D$i' id='D$i' onclick='Estimate($i,\"D$i\")'></button>";
                                    echo"<button type='button' name='E$i' id='E$i' onclick='Estimate($i,\"E$i\")'></button>";
                                    echo"<button type='button' name='F$i' id='F$i' onclick='Estimate($i,\"F$i\")'></button>";
                                echo"</Colu>";
                            echo"</Row>";
                        }

                    ?>

                    <script>

                        //checks which seats are available and which are not

                        let Seats = <?php echo isset($Seats) ? json_encode($Seats) : ""; ?> ;

                        for ( const seat of Seats)
                        {
                            console.log(seat)
                            document.getElementById(seat).style.backgroundColor = " rgb(255, 162, 134)";
                            document.getElementById(seat).style.borderColor = " rgb(246, 106, 75)";
                            document.getElementById(seat).setAttribute("disabled",true);
                        }

                    </script>

                </Plane>

                <desc>

                    <box style="background-color:rgb(255, 162, 134); border:solid; border-width:2px; border-color:rgb(246, 106, 75); width:40px;height:40px;"></box>
                    <box style="background-color:rgb(165, 235, 211); border:solid; border-width:2px; border-color:rgb(83, 140, 120); width:40px;height:40px;"></box>
                    <box style="background-color:rgb(199, 209, 223); border:solid; border-width:2px; border-color:rgb(69, 85, 100); width:40px;height:40px;"></box>
                    <box style="background-color:rgb(133, 152, 182); border:solid; border-width:2px; border-color:rgb(69, 85, 100); width:40px;height:40px;"></box>
                    <box style="background-color:rgb(241, 250, 252); border:solid; border-width:2px; border-color:rgb(69, 85, 100); width:40px;height:40px;"></box>

                </desc>

            </seatSelection>
            <MoreInfo>
                <?php

                    for ( $i = 1; $i <= $PassengerNo; $i++ )
                    {
                        echo "<num>Passenger $i</num>";

                        echo"<Block>";
                            echo" Select seat : <input name='P$i' id='P$i' required onfocus='this.blur();' ></input>";
                            echo" Cost : <input name='CC$i' id='CC$i' required onfocus='this.blur();'></input>";
                            echo" <button type='button' onclick='Erase($i)'></button>";
                        echo"</Block>";
                    }

                ?> 
                
                <Cost>
                    <button type='button' onclick='Cost()'>Estimate Cost</button>
                    <p>Final cost (€) : </p>
                    <input name='FinalCost' id='FinalCost'onfocus='this.blur();'>
                </Cost>
            </MoreInfo>

            <button type="submit" id="Submit" onclick='SetBookingMode()'>Book Tickets</button>
    </form>

    <footer>

    <p> Contact Number : 210 3530000 <br><br> Contact Email : Airdsairlines@gmail.com</p>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12586.198840455869!2d23.937224695673063!3d37.9409487204273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14a1901ad9e75c61%3A0x38b215df0aeeb3aa!2zzpTOuc61zrjOvc6uz4IgzpHOtc-Bzr_Ou865zrzOrc69zrHPgiDOkc64zrfOvc-Ozr0gzpXOu861z4XOuM6tz4HOuc6_z4IgzpLOtc69zrnOts6tzrvOv8-C!5e0!3m2!1sel!2sgr!4v1747322533807!5m2!1sel!2sgr" width="160" height="160" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade" style="border:solid; border-width: 2px; border-color:rgb(92, 159, 217); border-radius:10px;"></iframe>
    
    </footer>

</body>

</html>