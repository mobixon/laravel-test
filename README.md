# laravel-test

Test repository for framework test tasks, code examples etc. Each task is in separate branch.

## Super short(all in one image), for fast local development docker installation:

[http://127.0.0.1:8089](http://127.0.0.1:8089)

- with framework first go to public folder

- pull latest image (or for php 7.4 set tag to 7.4)
```
docker pull adhocore/lemp:8.0
```

- Base run with mysql creds (set `pwd` in unix)
```
docker run -p 8089:80 -p 8889:88 -p 3309:3306 -v ${pwd}:/var/www/html -e MYSQL_ROOT_PASSWORD=laravel_test_root_123 -e MYSQL_DATABASE=laravel_test_1 -e MYSQL_USER=laravel_test -e MYSQL_PASSWORD=laravel_test_123 --name lemp -d adhocore/lemp:8.0
```

 for postgres you can pass in similar env as for mysql but with PGSQL_ prefix

- To enter container shell
```
docker exec -it lemp sh
```

table for aditional ports and services mapping

| Name	            | Port |
|------------------|------|
| adminer	         | 80 |
| beanstalkd	      | 11300 |
| elasticsearch	   | 9200,9300 |
| mailcatcher	     | 88 |
| memcached	       | 11211 |
| MySQL(maria db)	 | 3306 |
| nginx	           | 80 |
| PHP	             | 9000 |
| PostgreSQL	      | 5432 |
| rabbitmq	        | 5672 |
| redis	           | 6379 |

More details on [adhocore/lemp](https://hub.docker.com/r/adhocore/lemp)
