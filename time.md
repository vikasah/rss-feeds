#Initial

The first step was to create an environment that can make things more automated and easier during the project life time.
I had set up npm packages and installed scss and created gulpfile which would configure settings and all development to be easily managed.

With the help of browsersync, any changes made within the code would reflect in the browser immediately.

#Connecting to the database and doing crud

Once the project root was all set and everything was working as planned, the next steps were to create the connection between the database,
since this is the most important part in the entire application, because without this the application is of no point. 

Created a class RssFeedsConnection, which handled all the insertions, updates and deletions as well as setting up the database and closing the database. With this done, the base was created and now I could use the methods to test whether commands are connecting with the database or not. 

It also became the model, so now a controller was needed to handle anything that is passed, filtering any unneeded data before touching the model.

#CRUD

This is where CRUD.php comes into play. It acted as a controller between the view and the model. It had basic duties to perform such as update, delete, insert, show, etc... Value would be passed into this class methods and it would filter before passed to the database, which checks and inputs the value. 

When creating this app, it was important there was a middleware between the view and the database. I wanted this app to perform without refreshing the browser or doing POST calls that would take a user to a new screen. Everything had to be done within the same session.

#App pretty much working at this stage

Now it was a matter of connecting through ajax, making ajax calls to the server.php file and testing out my classes. When building this, I had to keep in mind how the user will interact with the app as well, so it had to be created in a way that felt easy to use and was reusable throughout. 

Once the ajax calls were working after a few tweaks, it was now a matter of parsing the XML data and showing it nicely for the user. So an XMLParser class was created to help with this. It would accept data that contained xml and reject the rest.

With this, the app was complete and it was a matter of styling and documenting the rest.