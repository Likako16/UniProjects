from struct import *
import socket

#-----------------------------------------------------------------------------------------------------------------------------------------------------
Header = 240
# Above port 4000 all ports are inactive when not used 
Port = 5050
# Getting the name of our computer
Server = socket.gethostbyname(socket.gethostname())
# When we bind our socket to a specific addredss, it needs to be in a tupple. First the server and then the port in which that server is running off of
address = (Server, Port)
Format = 'utf-8'
# Making a disconnect message
Disconnect_Message = "!DISCONNECT"

# Creating a socket
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
# Now we bind the socket to this address. So anything that connects to this address to this address, will hit this socket.
server.bind(address)

#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                         IMPLEMENTATION OF MULTIPLICATION
def Multiplication(conn):
    
    MyltipliedNumbers = 1
    no_Numbers=0
    ready = False

    error=""

    while not ready:
        
        if no_Numbers == 0:
            conn.send(( error + "You can multiply up to 10 numbers from (-5) - (5) \nInsert a number: ").encode(Format))
            number = Unpack(conn)
        elif no_Numbers < 2:
            conn.send(( error + "Insert another number: " ).encode(Format))
            number = Unpack(conn)
        else:
            conn.send(( error + "Insert another number: (If you want to stop type ' = ')").encode(Format))
            number = Unpack(conn)
        
        try:
            int(number)
        except:
            if(number == "="):
                if no_Numbers > 1:
                    ready = True
                    continue
                else:
                    error="Need atleast 2 numbers!\n"
                    continue
            else:
                error="Only numbers are allowed!\n"
                continue
        else:
            number = int(number)

            if number<-5 or number>5:
                error="Only numbers from (-5) to (5) are allowed!\n"
                continue

        no_Numbers += 1
        
        MyltipliedNumbers = MyltipliedNumbers * number 
        error=""

        if no_Numbers == 10:
            ready = True

    return("The total is : " + str(MyltipliedNumbers)  + "\n" )


#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                            IMPLEMENTATION OF AVERAGE
def Average(conn):
    Sum = 0
    no_Numbers=0
    ready = False

    error=""

    while not ready:
        
        if no_Numbers == 0:
            conn.send(( error + "You can calculate the average of up to 20 numbers from (0) - (200) \nInsert a number: ").encode(Format))
            number = Unpack(conn)
        elif no_Numbers < 2:
            conn.send(( error + "Insert another number: " ).encode(Format))
            number = Unpack(conn)
        else:
            conn.send(( error + "Insert another number: (If you want to stop type ' = ')").encode(Format))
            number = Unpack(conn)
        
        try:
            int(number)
        except:
            if(number == "="):
                if no_Numbers > 1:
                    ready = True
                    continue
                else:
                    error="Need atleast 2 numbers!\n"
                    continue
            else:
                error="Only numbers are allowed!\n"
                continue
        else:
            number = int(number)

            if number<0 or number>200:
                error="Only numbers from (0) to (200) are allowed!\n"
                continue

        no_Numbers += 1
        
        Sum = Sum + number 
        error=""

        if no_Numbers == 20:
            ready = True

    return("The Average is : " + str(Sum/no_Numbers) + "\n" )

#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                          IMPLEMENTATION OF SUBTRACTION
def Subtraction(conn):
    set1 = []
    set2 = []
    no_Numbers = 0
    ready = False
    current_set=1
    error=""

    while not ready:
        
        if no_Numbers == 0 and current_set == 1:
            conn.send(( error + "The sets must have the same number (2) - (10) of values of (0) - (60000)\nInsert a number into set" + str(current_set) + " : ").encode(Format))
            number = Unpack(conn)
        elif no_Numbers < 2:
            conn.send(( error + "current set : " + str(current_set) + "\nInsert another number: " ).encode(Format))
            number = Unpack(conn)
        else:
            conn.send(( error + "current set : " + str(current_set) + "\nInsert another number: (If you want to stop type ' = ')").encode(Format))
            number = Unpack(conn)
        
        try:
            int(number)
        except:
            if(number == "="):

                if no_Numbers > 1:
                    if current_set == 1: 
                        current_set = 2
                        no_Numbers = 0
                        continue
                    elif current_set == 2 and len(set1) == len(set2) :
                        ready = True
                        continue
                    else:
                        error="Set1 and Set2 must have the same size!\n"
                        continue
                else:
                    error="Need atleast 2 numbers!\n"
                    continue

                if current_set == 1: 
                    current_set = 2
                    no_Numbers = 0
                    continue
                elif current_set == 2 and len(set1) == len(set2) :
                    ready = True
                    continue
                else:
                    error="Set1 and Set2 must have the same size!\n"
                    continue
            else:
                error="Only numbers are allowed!\n"
                continue
        else:
            number = int(number)

            if number<0 or number>60000:
                error="Only numbers from (0) to (60000) are allowed!\n"
                continue

        no_Numbers += 1

        if current_set == 1:    
            set1.append(number)
        else:
            set2.append(number)

        error=""

        if no_Numbers == 10:
            if current_set == 1:
                current_set = 2
                no_Numbers = 0
            else:
                ready = True

        if current_set == 2 and len(set1) == len(set2):    
            ready = True
        
    return ("The answer is : " + str([xi-yi for xi, yi in zip(set1, set2)]) + "\n" )
#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                        IMPLEMENTATION OF MESSAGE UNPACKING
def Unpack(conn):

    reply = conn.recv(4)

    Type,Length = unpack_from('!HH', reply, 0)

    Padding=(4 - Length % 4) % 4

    RestOfReply = conn.recv(Length+Padding-4)

    Service, Phase, Data = unpack_from('!HH'+str(Length-2*4)+'s', RestOfReply, 0)

    Data = Data.decode('utf-8')

    return Data

#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                          IMPLEMENTATION OF CLIENT SERVICE FUNCTION (ONCE ALREADY CONNECTED)
def service(conn, addr):
    print("")
    print(f"-- New connection made")
    print(f"-- Welcome {addr}")
    error=""
    answer=""

    connected = True
    while connected:

        conn.send((error + answer + "Select Service : \nType \n (1)-> Multiplication \n (2)-> Calculating Average \n (3)-> 2 Set Subtraction \n (4)-> Exit").encode(Format))
        error=""
        answer=""

        Data = Unpack(conn)

        if Data == "1":
            print(f"-- User {addr} is now Multiplying . . .")
            answer = Multiplication(conn)   
        elif Data == "2":
            print(f"-- User {addr} is now Calculating Average . . .")
            answer = Average(conn)
        elif Data == "3":
            print(f"-- User {addr} is now Subtracking . . .")
            answer = Subtraction(conn)
        elif Data == "4":
            print(f"-- User {addr} has disconected . . .")
            conn.send("Exit".encode(Format))
            connected=False
        else:
            error="Invalid Option\n"
          
#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                      IMPLEMENTATION OF STARTING FUNCTION
def start():
    #Listening for any available connections
    print("- LISTENING ... ")
    server.listen()

    #While somebody remains connected
    while True:
        conn,addr = server.accept() #Blocking any other new connection. Withholds information of the current connection
        service(conn, addr)
        conn.close()

#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                                    MAIN
print("--- S E R V E R  N O W  R U N N I N G . . . ---")
start()