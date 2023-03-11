# LeagueOAuth2Server

To implement OAuth2 module were used manual from https://davegebler.com/post/php/build-oauth2-server-php-symfony

### 1. Install LeagueOAuth2ServerBundle

To install `OAuth2` server Bundle use:
```bash
$ composer require league/oauth2-server-bundle
```

After installing package you shoud have added four new variables in your `.env` file

```dotenv
OAUTH_PRIVATE_KEY=%kernel.project_dir%/config/... #jwt/private.key or .pem
OAUTH_PUBLIC_KEY=%kernel.project_dir%/config/...  #jwt/public.key  or .pem
OAUTH_PASSPHRASE=06c6a11efe2cb35f5d6054ec215ae933
OAUTH_ENCRYPTION_KEY=5a0edfbd1da2e6311d1d94d4b73e7721
```

Additionally install `CORS` Bundle
```bash
$ composer require nelmio/cors-bundle
```


##### Docs

- https://github.com/thephpleague/oauth2-server-bundle
- https://github.com/thephpleague/oauth2-server
- https://github.com/nelmio/NelmioCorsBundle

### 2. Generate KeyPair

At first config your keypair's path in `.env` file. Next go to specific location and generate new key files.

**Remember! Keys must be generated in path according to paths in `.env` file**

```dotenv
OAUTH_PRIVATE_KEY=%kernel.project_dir%/config/jwt/private.key
OAUTH_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.key
```

```bash
$ jwt rsa -in var/keys/private.key -pubout -out var/keys/public.key
```

You can change localization as you please eg. to `var/keys` or `config\openssl`

### Encription

You can also (re-)generate your own encription key

```bash
$ php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
```

Copy result and change value in `.env` file

```dotenv
OAUTH_ENCRYPTION_KEY=   #paste result here
```

### 3. Config Security.yml

Define two new firewals in your project to allow to authorize request for anonymous users. 
[More details](https://github.com/thephpleague/oauth2-server-bundle/blob/master/docs/basic-setup.md)

```yml
   firewalls:
        #firewalls according to oauth2-server-bundle docs https://github.com/thephpleague/oauth2-server-bundle/blob/master/docs/basic-setup.md
        api_token:
            pattern: ^/api/token$
            security: false
        api:
            pattern: ^/api
            security: true
            stateless: true
            oauth2: true

```

### 4. Config custom client class

First define new parameters pointed to your client class.
Here we use `sonata` so our class is according to sonata specification.

But you can also use your own Entity model created via Doctrine.

```yaml
# sonata_admin.yaml
parameters:
    league.oauth2_server.client.classname: App\OAuth2\Entity\Client
```

Next plug in client model in oauth server client configuration

```yaml
# league_oauth2_server.yaml
client:
    # Set a custom client class. Must be a League\Bundle\OAuth2ServerBundle\Model\AbstractClient
    classname: '%league.oauth2_server.client.classname%' # instead of League\Bundle\OAuth2ServerBundle\Model\Client
```


### 5. Generate database

[LeagueOAuth2Server](https://github.com/thephpleague/oauth2-server-bundle) Bundle provides previously prepared databases. 
To get them you need to make new migration using:

```bash
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Now you should have four new tables in your database schema

```
*
|- oauth2_access_token
|- oauth2_authorization_code
|- oauth2_client
|- oauth2_refresh_token
```

<span style="color:red"><b>This tables aren't mapped to entities in your files, so you don't find them in your project.</b></span>

### 6. Create client

To create example client look [here](https://github.com/thephpleague/oauth2-server-bundle/blob/master/docs/basic-setup.md)

### 7. Get access token

Example how to execute HTTP request to get access token you can find in [oauth2_get_access_token.http](../http/oauth2_get_access_token.http) file.

**Example Response**

```http request
HTTP/1.1 200 OK
Cache-Control: no-store, private
Content-Type: application/json; charset=UTF-8
Date: Mon, 13 Feb 2023 21:57:23 GMT
Pragma: no-cache
X-Debug-Token: c19fc6
X-Debug-Token-Link: https://localhost:8000/_profiler/c19fc6
X-Powered-By: PHP/8.1.5
X-Robots-Tag: noindex
Content-Length: 725

{
  "token_type": "Bearer",
  "expires_in": 3600,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJpZGVudGlmaWVyX2FiYyIsImp0aSI6IjY2N2Y1NzgzZWRjZjRmNzQxNDdjYmE1MTEzYWE2MzlhNGI5N2RmOWRlYmE2MDIzMTExZmZjZWU0MGVkODI0NTYxOWRhYWEwMGNjMWVhYTY3IiwiaWF0IjoxNjc2MzI1NDQyLjg4Njc5NywibmJmIjoxNjc2MzI1NDQyLjg4NjgxLCJleHAiOjE2NzYzMjkwNDIuNDQwMzIyLCJzdWIiOiIiLCJzY29wZXMiOlsiZGVmYXVsdCJdfQ.EgOj49GUKGTnjFF7za8vdQI42k-esa51o6fk75D1JTPcQCTR5EiTwmjt-3rlK37yVT72sQ-YPsrA_80m_Vp-NjH1oziPssQhzKMab4QdX43E1H4FjHJR_7QNIdDkMe-NRxPQj4y2L6JF5cJOOw1JIEwvAYrjnLcn_OMz2qj1qpkOSnCQJ3lhl21HUy_9Mn5cbHRKgsj5c3K5qBG_-igpFvtFVmJxhzi0TsSOJQYiqMWLgec3tlVSx7kvLEQi-n0KAdl9CIfgCVsr2NR_vEST_2xS7EZT7bm-MzkfP6uwS-O2Ud5kuTXtyayKAqX75h71pJ6cAwXlDvYnQGvokl4m3Q"
}
```

Most important part is one containing token which will be attaching as `Bare token` to authorize next requests in service

```json
{
  "token_type": "Bearer",
  "expires_in": 3600,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJpZGVudGlmaWVyX2FiYyIsImp0aSI6IjY2N2Y1NzgzZWRjZjRmNzQxNDdjYmE1MTEzYWE2MzlhNGI5N2RmOWRlYmE2MDIzMTExZmZjZWU0MGVkODI0NTYxOWRhYWEwMGNjMWVhYTY3IiwiaWF0IjoxNjc2MzI1NDQyLjg4Njc5NywibmJmIjoxNjc2MzI1NDQyLjg4NjgxLCJleHAiOjE2NzYzMjkwNDIuNDQwMzIyLCJzdWIiOiIiLCJzY29wZXMiOlsiZGVmYXVsdCJdfQ.EgOj49GUKGTnjFF7za8vdQI42k-esa51o6fk75D1JTPcQCTR5EiTwmjt-3rlK37yVT72sQ-YPsrA_80m_Vp-NjH1oziPssQhzKMab4QdX43E1H4FjHJR_7QNIdDkMe-NRxPQj4y2L6JF5cJOOw1JIEwvAYrjnLcn_OMz2qj1qpkOSnCQJ3lhl21HUy_9Mn5cbHRKgsj5c3K5qBG_-igpFvtFVmJxhzi0TsSOJQYiqMWLgec3tlVSx7kvLEQi-n0KAdl9CIfgCVsr2NR_vEST_2xS7EZT7bm-MzkfP6uwS-O2Ud5kuTXtyayKAqX75h71pJ6cAwXlDvYnQGvokl4m3Q"
}
```