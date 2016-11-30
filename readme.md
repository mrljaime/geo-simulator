Geo Simulator

This is a mini-app that just calculate the distance between two points.
One of that points means a user, who needs that somebody gets his car 
and gets the car to parking.
The other point means a driver, who is waiting to be closest at somebody that needs his help.

The app has two main routes: 
   
    "/":    has the responsibility that show who is more closest of who.
            Also, when you invoked the next route, this will render automatically
            the new calculated options and will move the markers of the drivers.
            For next versions (because this is really poor), you'll can captured
            your own routes to the drivers, and so on, put the place where the "users"
            will be.
            This also has the main config of the app. What that means? Means that the
            user can change how many users of drivers will they interacted in the map, 
            and you can put this shit on pause, and the next route will say: 
                "Hey men! We'll be back soon.";
            ;
    
    "/putNextPosition":
            when you invoked this route, you will get a json response with this:
                "{code: 200}"
            that means that all next positions for drivers will be put on the line.
            NOTE: In next versions this well be integrated with the main UI.
            
And then, three more steps to make this shit work (That I think):
    
    1.  Write your access to de database in the archive:
        "config/database.php"
    2.  Run the shell script (that will ask you for user and password 
        for your database). This script will create and stored stuff in the 
        database to make this run correctly, and so on, will download 
        all dependencies, from nodejs for the bi-directional communication
        when you invoked to "/putNextPosition", and from php, that will 
        download all laravel stuff.
    3:  In two different terminals run the next commands and... Good Luck!
        php artisan serve
        node/nodejs webSocket.js
        
Thank you!
I make this change to remind me that I will do this for an app and the server side implementation
for web sockets will be write in Java.

Cheers.
Jaime.