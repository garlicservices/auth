Garlic Authorization bundle
=====================

## JWT token authorization bundle

### Installation

#### 1. Run:

```bash
$ composer require garlic/auth
```

#### 2. Add to .env.dist (.env):

```bash
# Required
JWT_PUBLIC_KEY_PATH='%kernel.root_dir%/../path/to/public.pem' #By default is %kernel.root_dir%/../vendor/garlic/auth/src/Resources/jwt/public.pem

# Optional
JWT_PRIVATE_KEY_PATH=''
JWT_KEY_PASS_PHRASE='passPhrase'
JWT_TOKEN_TTL='3600'
JWT_USER_IDENTITY_FIELD='email'
JWT_USER_CLASS='Garlic\Auth\Security\User'
```

### Using

#### 1. Make JWT token from the authorization service

#### 2. Add JWT token to to request headers:

Authorization: Body.Of.Token
