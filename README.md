# quizmanager
This is an upload of my Level 4 Software development project in 2019. The project was to develop a quiz manager web application.

## Feature (stated requirements)
- Database-driven website
> I used MySQL to store all the data required for the application, and used PHP to pull the relevant data onto the web page when needed.
- Designed to be straight forward to rebrand
> I used Bootstrap and CSS to style the front end of the application. I included CSS variables, so the client can easily change these to rebrand the site, such as colours and fonts.
- User’s credentials (username, password, permissions) pre-configured
> I have provided a PHP script to generate these user accounts once the databases have been set up.
- Stored passwords hashed
> The users passwords are hashed before stored to the database when the accounts are being generated.
- Permission levels should be one of {Edit, View. Restricted}, where Edit means the ability to add, delete and change questions and answers, View means the ability to views questions and answers, and Restricted means the ability to view questions only.
>  I generated one Edit, View and Restricted account. The accounts include their own permissions where they can only do the specified actions.
- The website will need to maintain user session state while the user is logged in.
> I used a session state when the user logs in. This stores the relevant variables, so the platform knows their user permissions when they are logged in. If they logout or close the browser, the session is destroyed.
- User has the ability to logout, which will take them back to the login page.
> A logout button is provided, which destroys the session state and takes them back to the login page.

## Feature (my assumptions)
- The platform has a login page where the user can log into their account. If the user tries to access the other pages of the application without logging in first, they will need to automatically redirected back to the login page. This is so only known users can access the site..
- Once logged in, they will be presented with a “Log out” button on all pages of the site. This is so they can easily log out of the application wherever they are in the site.
- An access denied page. If a user tries to manually access a page via the URL they do not have the correct permissions for, they will be directed to this page. This will tell them they do not have the correct permissions to access this page and will have a button to go back to their previous page.
- An error page. This is so if a request is made by the user which is invalid, for example the id parameter (part of my solution) is missing from the URL string or is not valid, they will be directed to this page.
- Form validation. Form validation has been implemented for when the user has inputted incorrect data, so database issues are prevented. For example if a user inputs an incorrect password on the login page, they should see this error when they click submit.
- The user with the Edit permissions is able to edit the quiz name. If they have made a typo, or simply want to update the quiz name, they should have the ability to do so on the platform.
- If the user clicks the delete button, they should be taken to a delete page with a warning before the record is deleted. This is so they can confirm they want to delete it, so they don’t do it by accident.

## Setup
1. Run Apache and MySQL on your webserver.
2. Create the database and tables (I have provided MySQL which can be found in the root).
    1. Take the code located in database.sql in the sourcecode and execute it in MySql on your server.
    2. Next, edit the connections in my project so they point to your MySQL database. (config.php and insert_users.php)
3. Put my project folder quizmanager on your web server.
4. Run the ‘insert_users.php’ file located on your server to generate the user accounts.
5. You should now be able to access the quiz manager. Go to the login.php page. Following credentials below:
    - 'Username: Edit' 'Password: letmeinedit'
    - 'Username: View' 'Password: letmeinedit'
    - 'Username: Restricted' 'Password: letmeinedit'

## Technologies
- HTML
- CSS
- Bootstrap
- PHP
- MySQL
- MySQLi
