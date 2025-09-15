from struct import *
import socket

#-----------------------------------------------------------------------------------------------------------------------------------------------------
Header = 240
# Above port 4000 all ports are inactive when not used 
Port = 5050
# Getting the name of our computer
Server = socket.gethostbyname(socket.gethostname())
address = (Server, Port)
Format = 'utf-8'

client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client.connect(address)
#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                           IMPLEMENTATION OF HEADER 
#
#    THE IDEA : (Τις μεταβλητές service, phase και type δεν τις χρησιμοποίησα πουθενά, τις έστειλα μόνο για εξάσκηση)
# 
#    0____________16_____________31     
#    |            |              |
#    |    TYPE    |    LENGTH    |   4 bytes
#    |____________|______________|
#   32___________________________63
#    |            |              |
#    |  SERVICE   |     PHASE    |   8 bytes
#    |____________|______________|    
#   64___________________________95 
#    |                    |      |
#    |        DATA        |  PA  |   12 bytes
#    |____________________|______|     
#

def Packing(message):

    #Initializing Data
    Type = 0
    Service = 0
    Phase = 0
    Data = message
    #Calculating length
    Length = 2*4 + len(Data)

    Padding = (4 - len(Data) % 4) % 4

    nb = bytes(Data, 'utf-8')

    Pack = pack('!H', Type)
 
    Pack = Pack + pack('!H', Length)

    Pack = Pack + pack('!H', Service)
 
    Pack = Pack + pack('!H', Phase)

    Pack = Pack + pack( str(len(Data))+'s', nb)


    if( Padding > 0 ):
        Pack = Pack + pack(str(Padding)+'x')

    #Sending all to the server
    client.sendall(Pack)
#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                        IMPLEMENTATION OF CLIENT INPUT
def Inputing():
    
    What_i_want_to_send = input()    
    Packing(What_i_want_to_send)     
#-----------------------------------------------------------------------------------------------------------------------------------------------------
#                                                             MAIN


#                                                            exit()
#                                                              Λ
#                                                              |                                       
# This loop contains only    Λ   receiving -> checking if it wants me to exit -> If not, reply  ->   -|
#                            |_         <-         <-         <-         <-         <-         <-     v
while True:
    #Receiving message from server
    server_message = client.recv(Header).decode(Format)
    # If message is 'Exit' it tells me to exit
    if server_message == "Exit":
        exit(0)
    print(server_message)
    #Replying
    Inputing()


