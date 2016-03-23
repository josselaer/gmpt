# GMPT

##GitHub Issues

###GUI

####GUI task titles should be formatted with the following syntax:

``` html
[GUI] - <ElementName> <TaskType> 
```
####Task Types include:
+ Mockup
  - Mocking up the view via HTML and CSS
+ Functionality
  - Functionality involves making the different parts of the view work

####GUI descriptions depend on the TaskType
+ Mockup
  - List all the different components which must be visible in this view. If the view is more dynamic, describe all the behavior which the mockup must have
+ Functionality
  - Describe the specific behavior of the page. List the specific requests which must be called, and what exactly should occur when those requests occur. 

===

###DB

####DB task titles should be formatted with the following syntax:

``` html
[DB] - <FeatureName> <TaskType> 
```
####FeatureNames include:
+ Users
+ Groups
+ Meetings
+ Chat
+ Stats

####Task Types include:
+ PHP
  - PHP tasks include all tasks which must be completed in the application layer of the API.
+ DB
  - DB tasks have to do with DDLs, creating and making actual changes to the database and generating test data.
+ Query
  - Querying tasks involve writing queries to accomplish various tasks.


####DB descriptions depend on the TaskType
+ PHP
  - Describe exactly what the PHP layer should be receiving and sending. In other words, describe the input and output of each piece.
+ DB
  - Describe the specific characteristics of the database schema. In other words, describe how the database will store data, as well as how the data needs to be used.
+ Query
  - Describe the correct output for the query as well as what the query intends to accomplish
+ General
  - Describe any special behavior of the feature in question

__There will be some tasks which do not fit this format. These tasks should try to incorporate at least one or more of the components shown above. However, it is understable if it cannot be done.__


##Development Environment Setup for DB

1. Choose where you want your project directory to be on your local file system. Navigate to this location using your command prompt. Execute the following command:
  * git clone https://github.com/scotch-io/scotch-box
2. Run the following commands:
  * vagrant up
  * vagrant ssh
3. Type the following IP address in your browser: 192.168.33.10. If you see a website for scotch-box, then you have successfully installed scotch-box.
4. Run the following commands:
  * cd /
  * cd var/www/public
5. You should be able to see the index.php file which you saw in your browser. If you see this, you are in the correct directory. If you wish to create a clean working directory, run the following command: 
  * composer create-project slim/slim-skeleton api
6. If you wish to run an existing application, clone your git repository to this folder.
7. The web server is currently routed so that the document root is var/www/public/. You must navigate to the directory of choice after this point. For instance, if you have download a new slim/skeleton framework, you may have to use the following URL: http://192.168.33.10/api/public/.
8. __Remove the index.php file provided by scotch-box__
