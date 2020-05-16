# Decryptoid
 Server-Side Web Programming Group Project with Arlan Prado and Joshua Sjah

Assignment:
Your web application lets the users encrypt and decrypt texts in input (from an input box or a file). The user can select from a list of ciphers and specify if it needs encryption or decryption.

   It would be very cool to implement a logic able to detect the cipher used to encrypt a text in input, without let the user specify it (this would make the project "Incredibly Intense", though).

 

You will have to:

Build a web page that:

   Ensures a secure Session mechanism.
   
   Allows the user to sign up and log in.
   
   Allows the user to submit a text file (extension .txt ONLY).
   
   Allows the user to submit text in an input text box.
   
   Allows the user to select a cipher from a list and if the input should be encrypted or decrypted.
   
 

Build a web application that:

   Reads the file in input and the cipher selected, then applies this cipher (encryption or decryption, based on the user's input).
   
   Reads the text from the input text box, if any, and the cipher selected, then applies this cipher (encryption or decryption, based on the user's input)
   
   Implements these ciphers (you cannot use libraries):
   
   Simple Substitution
       
   Double Transposition
       
   RC4
       

 

Build a MySQL database that:

   Stores the input texts from that specific user (if logged in), the cipher used and the timestamp at the moment of the creation of the record.
   
   Stores the information related to the user accounts (username, password and email) in the most secure way of your knowledge.
   
   All these fields must be validated:
   
   -The username can contain English letters (capitalized or not), digits, and the characters '\_' (underscore) and '-' (dash). Nothing else.
           
   -The email must be well formatted.
           
   -The password can have limitations of your choice, if you think it's worth it.

  

If your group is formed by two or three people, you have to add these requirements:

   Your web application should implement also the DES cipher.

 
