# Symfony2 RESTful API example

The backend is a REST API secured by OAuth2 based on [Symfony3](http://symfony.com).

## Installation

### Requirements

- [Composer](https://getcomposer.org/download)

### Steps

```bash
$ git clone https://github.com/ruslana-net/ai-catalog-api.git
$ composer install
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
$ php app/console doctrine:fixtures:load
```

### Create OAuth client

```bash
php bin/console ai:oauth-server:client:create --redirect-uri=http://yourapp.com/ --grant-type=token --grant-type=password --grant-type=authorization_code ClientName
```

### OAuth paths
```bash
http://yoursite.ru/doc/api
http://yoursite.ru/oauth/token
http://yoursite.ru/oauth/auth
```

## Running tests

```bash
$ phpunit
```

## Demo

[Demo]()

## License

[MIT](https://github.com/ruslana-net/ai-catalog-api/blob/master/LICENSE)