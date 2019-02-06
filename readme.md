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
