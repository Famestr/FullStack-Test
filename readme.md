This is a project that I created for a job application that I recently applied for. I used Lavarvel as the PHP framework and Vue Js for the JavaScript framework in this project. Currently, I'm actively seeking an employment / freelance position, so if you like what you see and would like to use my skill, feel free to contact me on my email at fahimbhimani@hotmail.com

================
Introduction
================

We request that you build a dynamic web page using open source libraries and tools. 
PHP, Javascript and CSS are the only core tools we want you to use for this demonstration of your skill set. 
Using PHP or Javascript frameworks is highly encouraged and would score you extra points (as long as the end result works without too much configuration).

================
PHP Requirements
================

We would like to see you use PHP to select data from a mysql database table (see included members.sql) and produce a JSON view of this data. 
Your javascript application will consume and display this data according to the "Javascript / UI Requirements" section below. 

Extra points for using an PHP MVC framework to achieve this part. 
The URL should be able to take 3 GET or POST string arguments : "firstname", "surname" and "email". 
These arguments should be used to limit the results in the JSON output to only include records the search strings match in their respective MySQL database fields. 

============================
Javascript / UI Requirements
============================

For the user interface functionality we would like you to use HTML, Javascript and CSS. Showing your skills with a modern javascript framework would be advantageous.

Once you have created the JSON web service to consume you should build a javascript application from the following requirements:

1. Consume the above web service and make the JSON string returned available to local Javascript.

2. Generate a HTML table from the JSON array that contains all the fields from the database table and displays all of the records. 
This table should employ pagination.

3. Create text fields that when typed into, limits the results based on the firstname, surname and email of the member and redraws the table with the limited results.
