<?php

namespace Garlic\Auth\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class AuthorizationExtension
 */
class AuthorizationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        # Config for JWT bundle
        $jwtConfig = [
            'private_key_path' => getenv('JWT_PRIVATE_KEY_PATH') ?? '~',
            'public_key_path' => getenv('JWT_PUBLIC_KEY_PATH') ?? '%kernel.root_dir%/../vendor/garlic/auth/src/Resources/jwt/public.pem',
            'pass_phrase' => getenv('JWT_KEY_PASS_PHRASE') ?? '~',
            'token_ttl' => getenv('JWT_TOKEN_TTL') ?? '~',
            'user_identity_field' => getenv('JWT_USER_IDENTITY_FIELD') ?? 'email',
            'encoder' => [
                'service' => 'lexik_jwt_authentication.encoder.lcobucci',
                'crypto_engine' => 'openssl',
                'signature_algorithm' => 'RS384',
            ],
            'token_extractors' => [
                'authorization_header' => [
                    'enabled' => 'true',
                    'prefix' => '',
                    'name' => 'Authorization',
                ]
            ]
        ];

        $container->prependExtensionConfig('lexik_jwt_authentication', $jwtConfig);

        # Security settings for Auth bundle
        $securityConfig = [
            'providers' => [
                'authorization_header' => [
                    'lexik_jwt' => [
                        'class' => (getenv('JWT_USER_CLASS')) ?? 'Garlic\Auth\Security\User'
                    ],
                ]
            ],
            'firewalls' => [
                'main' => [
                    'pattern' => '^/',
                    'stateless' => 'true',
                    'anonymous' => 'true',
                    'guard' => [
                        'authenticators' => [
                            'lexik_jwt_authentication.jwt_token_authenticator'
                        ],
                    ]
                ]
            ]
        ];

        $container->prependExtensionConfig('security', $securityConfig);
    }
}
