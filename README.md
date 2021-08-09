## Git
```bash
git clone http://git.qubit.tv:8888/vod/gateway.git;
```

## Structure

La aplicacion esta definida por packages 
```bash
test_laravel/
├── packages/
│ 	└── Zebrands/
│        ├── ExampleOtroPaq/   <- no existe es un ejemplo
│        └── Catalogue/
```


## Configuration
Configura las vairiables d entorno a usar como database, mail entre otras.
```bash
test_laravel/
├── .env
```

## Installation - simple

Crea tu DATABASE en local example:

```bash
CREATE DATABASE bd_zebrands;
```

```bash
$ composer dumpautoload; 
```
```bash
$ php artisan migrate;
```
```bash
$ php artisan db:seed; 
```

## Execute - simple

Define un puerto no usado
```bash
$ php -S localhost:8000 -t RUTA_ABSOLUTA/test_laravel/public/
```

## Via docker-compose, NO TERMINADO POR TIEMPO

start
```bash
$ docker-compose up -d
```
stop
```bash
$ docker-compose down
```

## Installation - docker

docker exec -ti zblaravel bash
```bash
$ composer dumpautoload; 
```
```bash
$ php artisan migrate;
```
```bash
$ php artisan db:seed; 
```

## Execute - docker

http://localhost:9002 <-servico
