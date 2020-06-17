# Installation #

For this project, I used vagrant homestead environment. It comes with built in packages - npm, composer, php, etc...
However you can use whatever local environment you like such as MAMP, WAMP, anything that runs apache server locally.

The following steps will help you get the app running:

    . Get your local environment
    . Make sure PDO is available for you, this is required to run the database connections
    . Install the .sql file that is provided
    . Change your configurations on server.php in order to connect to the correct database.
    . If all above is done correctly, it should work. You should see a message that shows you the current list within the database along with 2 links. If not, nothing should show apart from the dialog box with add link.

# Versions #

If in any case you would like to run the gulpfile, the versions that I had installed on my machines were the following for the different softwares:

    . npm  - 6.13.4
    . node - v8.17.0

