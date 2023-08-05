# TEST TASK FOR 1PAYMENT

### TASK

1. Создать окружение в докере php + nginx + mysql
2. Развернуть проект на symfony
3. Создать базу данных и таблицу, в которую разместить данные из файла test_data.csv
4. По адресу localhost:1234/find_range показывать страницу, где будет форма с полем ввода
5. При вводе в поле числа <= 19 знаков отображать id интервала в который попадает данное число, как если бы оно было дополнено нулями справа до 19 знаков
6. Если число не попадает на в один интервал, то выводить сообщение об ошибке

### Prerequisite

- Docker >=20.10.14
- Docker Compose >=v1.29.0

### Local environment setup

1.save .env.local file as .env in root directory

2.building Docker images
```
docker-compose build
```
3.Create and start containers
```
docker-compose up -d
```

4.Install dependencies
```
docker-compose exec app composer install
```

5.Running migrations
```
docker-compose exec app php bin/console doctrine:migrations:migrate
```

6.Importing test data
```
docker-compose exec app php bin/console interval:insert public/test_data.csv
```

### URL

```
http://localhost:1234/find_range
```
