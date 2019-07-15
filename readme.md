## Digico project

На Linux системе перед первым запуском контейнера запустить скрипт 

    bash start.sh
    docker-compose build

Запуск проекта 

    docker-compose build --build-arg base_env=$UID


Далее вам будет доступен сервер со следующими параметрами 

Адрес сайта http://localhost:9090/
Порт сокета: 2346

База данных
    
    User:  root
    Password: digico
    dbName: digico
    host(из проекта):  maria_db:3306
    host(из вне):  localhost:53306
    
    
    
Логи NGINX 
    
    storage/logs/nginx/
    
Добавление данных в бд 

    docker exec -it php_docker sudo -u www-data php artisan migrate:refresh
    docker exec -it php_docker sudo -u www-data php artisan db:seed

##Админ панель

Загрузка тестового администратора 
        
    php artisan db:seed --class=UserSeeder

    Пользователь: admin
    Пароль: admin


## GraphQL 
Usage
By default, the playground is reachable at 

http://<site_______name>/graphql

for auth user add Header 

    X-TOKEN-AUTH: <token>
Url for auth user 

    http://<site_______name>/graphql/user
    
Query
 
    {
      tracks(count: 51  ){
        paginatorInfo{
          count,
          total
        }
        data{
          id, track_name, song_text, 
          genre {
            name, 
            created_at, 
            image
          }, 
          user {
            username, 
            email
          }, 
          album{
            title, 
            author
          }
        }
      }
    }
        
        
        
## Запуск сокета для передачи сообщений

    docker exec -it php_docker sudo -u www-data php artisan workman start 

### WORKFLOW TRACK

    docker exec -it php_docker  sudo -u www-data php artisan workflow:dump track --class App\\Models\\Track
