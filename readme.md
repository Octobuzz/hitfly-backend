## Digico project

На Linux системе перед первым запуском контейнера запустить скрипт 

    bash start.sh
    docker-compose build

Запуск проекта 

    docker-compose up  


Далее вам будет доступен сервер со следующими параметрами 

Адрес сайта http://localhost:9090/

База данных
    
    User:  root
    Password: digico
    dbName: digico
    host(из проекта):  maria_db:3306
    host(из вне):  localhost:53306
    
    
    
Логи NGINX 
    
    storage/logs/nginx/
    

##Админ панель

Загрузка тестового администратора 
        
    php artisan db:seed --class=UserSeeder

    Пользователь: admin
    Пароль: admin


## GraphQL 
Usage
By default, the playground is reachable at /graphql-playground



http://localhost:9090/graphql


       {
         genres(count: 3, page: 1){
          paginatorInfo{
           total,
           lastPage
         }
           
         	data{
           	id,
             name,
         	} 
         }
       }
       
       
##### REPONSE 
       
       {
         "data": {
           "genres": {
             "paginatorInfo": {
               "total": 871,
               "lastPage": 291
             },
             "data": [
               {
                 "id": "64",
                 "name": "Kieran Hamill III"
               },
               {
                 "id": "65",
                 "name": "Garett Powlowski"
               },
               {
                 "id": "66",
                 "name": "Ms. Yvonne Kuhic"
               }
             ]
           }
         }
       }
        
        
        
