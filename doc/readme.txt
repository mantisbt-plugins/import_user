Mantis Users Importer
=====================

Function
---------
Imports/update one or more users from a CSV text file into Mantis.

Requirements
-------------
Made for and tested against Mantis version 2.x

Installation
------------
Install as any other plugin.

Usage
-----
Be aware that this importing functionality can make a mess in your database. It is recommanded that you make
a backup of your database before importing.

1. In the manager menu, select Import Users.

2. Select the file to import. Your file must be in a correct CSV format.
   The first line of the file can be a title line. 

3. Select the "Process file" button.


Lay-out CSV file
----------------
Please ensure following columns are available.
Title row is allowed.

Username
Real name
Email address
Password 
Access Level 
	Use the number value here ( see below)
	If omitted, default value for new accounts will be used
Send email
	0 =  do not send email
	1 =  Send email
	
User accounts will default to:
 -Not Protected
 -Enabled
 
# available access levels 
define( 'ANYBODY', 0 );
define( 'VIEWER', 10 );
define( 'REPORTER', 25 );
define( 'UPDATER', 40 );
define( 'DEVELOPER', 55 );
define( 'MANAGER', 70 );
define( 'ADMINISTRATOR', 90 );
define( 'NOBODY', 100 ); 