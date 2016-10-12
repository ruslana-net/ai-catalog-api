# Symfony2 RESTful API example

The backend is a REST API secured by OAuth2 based on [Symfony3](http://symfony.com).

## Installation

### Requirements

- [Composer](https://getcomposer.org/download)
- PHP 7
- MySQL 5.6

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
php bin/console ai:oauth-server:client:create --redirect-uri=http://yourapp.com/ --grant-type=token --grant-type=password --grant-type=authorization_code --grant-type=refresh_token ClientName

# get access_token
curl -X POST -d 'grant_type=password&username=your_username&password=user_password&client_id=your_client_id&client_secret=you_client_secret_key' http://yousite.com/oauth/token

# refresh access_token
curl -X POST -d 'grant_type=refresh_token&refresh_token=your_refresh_token&client_id=your_client_id&client_secret=you_client_secret_key' http://yousite.com/oauth/token
```

### OAuth paths
```bash
# Api doc
http://yoursite.com/doc/api

# OAuth2 token path
http://yoursite.com/oauth/token

# OAuth2 auth path
http://yoursite.com/oauth/auth
```

## Running tests

```bash
$ phpunit
```

## Demo

[Demo]()

## License

[MIT](https://github.com/ruslana-net/ai-catalog-api/blob/master/LICENSE)