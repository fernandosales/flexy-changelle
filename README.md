## Environment Setting

This project was developed with Docker containers, below are the docker images used:

- nginx:stable-alpine
- mysql:latest
- php:7.4-fpm

Let's start with the commands to upload containers.

```sh
cd Symfony
sudo docker-compose up --build -d
```

After a few minutes the terminal will return a message similar to this:
```sh
Successfully built ef6bdbd7708d
Successfully tagged symfony_php:latest
Pulling nginx (nginx:stable-alpine)...
stable-alpine: Pulling from library/nginx
540db60ca938: Pull complete
b824a2584ece: Pull complete
82d0e0426b2d: Pull complete
ed76aa154407: Pull complete
ef4cf5a20f8a: Pull complete
9d3441de5d5e: Pull complete
Digest: sha256:bac218df22fef66a173cfa65d0dfa0acca80a3a39df41665be742596977b6c7c
Status: Downloaded newer image for nginx:stable-alpine
Creating database ... done
Creating php      ... done
Creating nginx    ... done
```
It means we can continue.

Access the php container:
```sh
sudo docker-compose exec php /bin/bash
```
Now run the command to download symfony packages by composer:
```sh
composer install --prefer-dist
```
Check if the DATABASE_URL variable of env.local is the same as the line below:
```sh
DATABASE_URL="mysql://root:secret@database:3306/symfony_docker?serverVersion=8.0"
```

Check out the minimum requirements for Symfony with command:
```sh
symfony check:requirements
```

Prepare the database and tables with the commands:
```sh
symfony console doctrine:migrations:migrate
```

To populate tags in Crud, launch App Fixtures
```sh
php bin/console doctrine:fixtures:load
```
The End
