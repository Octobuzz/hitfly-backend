## Требования
Проверенно и работает на:
 - ОС: Ubuntu 16/18
 - docker: 19.03.7
 - docker-compose: 1.25.4,

Установить docker-compose:

    sudo curl -L "https://github.com/docker/compose/releases/download/1.25.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose

если что-то пошло не так, то полная инструкция тут [Install Compose](https://docs.docker.com/compose/install/#install-compose)

Установить Altair GraphQL Client клиент (для работы с API GraphQL):

    sudo snap install altairal

## Digico project
Список доступных команд 
    
    make help

Сборка (разовый запуск при установке либо изменениях в docker):

    make build

Запуск проекта:

    make up
    
Далее вам будет доступен сервер со следующими параметрами 

Адрес сайта http://localhost:9090/
Порт сокета: 2346

Адрес сайта ssr http://localhost:9091/
Порт сокета: 2346


База данных
    
    User:  root
    Password: digico
    dbName: digico
    host(из проекта):  hitfly_db:3306
    host(из вне):  localhost:53306
    
    
Добавление данных в бд 

    make mirgate
    make seed

##Админ панель

Загрузка тестового администратора 
        
    php artisan db:seed --class=UserSeeder

    Пользователь: admin
    Пароль: admin
        
## Запуск сокета для передачи сообщений

    make socket
