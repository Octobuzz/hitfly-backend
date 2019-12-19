## Digico project
Список доступных команд 
    
    make help


Запуск проекта 

    make build && make up
    
Далее вам будет доступен сервер со следующими параметрами 

Адрес сайта http://localhost:9090/
Порт сокета: 2346

Адрес сайта ssr http://localhost:9091/
Порт сокета: 2346


База данных
    
    User:  root
    Password: digico
    dbName: digico
    host(из проекта):  maria_db:3306
    host(из вне):  localhost:53306
    
    
Добавление данных в бд 

    docker exec -it php_docker sudo -u www-data php artisan migrate:refresh
    docker exec -it php_docker sudo -u www-data php artisan db:seed

##Админ панель

Загрузка тестового администратора 
        
    php artisan db:seed --class=UserSeeder

    Пользователь: admin
    Пароль: admin
        
## Запуск сокета для передачи сообщений

    make socket
