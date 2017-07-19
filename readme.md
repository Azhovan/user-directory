

**User Directory Project**
--------------------------

 
*NOTE* : 
since this is a laravel project, all instructions for install laravel need to be performed [Laravel Instruction](https://laravel.com/docs/5.4#configuration)

Please use below instruction : 

 1. clone this rerpository
 2.  apply proper permissions to framework folders
 3. run composer install && composer update
 4. set your database configuration (/app/config/database.php)
 5. set your memcache configuration (/app/config/cache.php)
 6. set elasticsearch configuration (/app/config/plastic.php)
 7. set your session mechanism (/app/config/session.php)
 8. Run **php artisan migrate** to create all database tables
 9. Run **php artisan key:generate**

----------

**Befor up an run the application Elastic Search should be runned** 

*NOTE* :
you may use .env file for your setting (except elasticsearch setting) . 

Main Module wroted for this project is **UserDirectory** which is contain below : 

 - **Config Directory** : contain all constant , messages
 - **Exceptions** : contain two sort of validation for application 1).elastic exceptions  2). validation exceptions
 - **Models** : contain database models 

----------
 - **Services** **: contain all business logic as below :**
 - **Services/Elastic** :  parsing the data which comes from elasticSearch server
 - **Services/Response** : generate response based on different strategy .
 - **Services/User** : all services related to user and its actions like searchable models, interaction with cache, search , ... 
 - **Services/Validator** :  all strategies for validate inputes goes here .
 - **Services/IService , ISearch, IUser** : parent  abstract classes | interfaces which are used by other components 

----------
**Events :**  when user is registered or it's data updated,  proper event will triggered for indexing the data and update the Cached data .

**controllers** : can be found at : 
 - /app/Http/Controllers/

**View** can be found at : 
 - /resources/views/

and finally **main JS file** and configuration are placed at : 
 - /public/js/user_directory.js