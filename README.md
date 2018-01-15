Garlic Authorization bundle
=====================

## JWT token authorization bundle

### Installation

#### 1. Run:

```bash
$ composer require garlic/auth
```

#### 2. Add to config.yml:

```yml
lexik_jwt_authentication:

    private_key_path: '%jwt_private_key_path%'
    public_key_path:  '%jwt_public_key_path%'
    pass_phrase:      '%jwt_key_pass_phrase%'
    token_ttl:        '%jwt_token_ttl%'
    user_identity_field: email

    encoder:
        service: lexik_jwt_authentication.encoder.lcobucci
        crypto_engine:  openssl
        signature_algorithm: RS384

    token_extractors:
        authorization_header:
            enabled: true
            prefix:  ''
            name:    Authorization

security:

    providers:
        jwt_provider:
            lexik_jwt:
                class: AuthorizationBundle\Security\User\JWTUser

    firewalls:
        main:
            pattern:   ^/
            stateless: true
            anonymous: true
            guard:
                authenticators:
                    - lexik_jwt_authentication.jwt_token_authenticator
```

#### 3. Add to parameters.yml:

```yml
parameters:
    jwt_private_key_path: ~
    jwt_public_key_path: '%kernel.root_dir%/../path-to-public.pem'
    jwt_key_pass_phrase: ~
    jwt_token_ttl: 3600
```

#### 4. Next, configure the application using symfony security.

### Using

#### 1. Make JWT token from the authorization service

#### 2. Add JWT token to to request headers:

Authorization: Body.Of.Token
