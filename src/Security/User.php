<?php

namespace Garlic\Auth\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

/**
 * Class JWTUser
 */
class User implements JWTUserInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $userType;

    /**
     * JWTUser constructor.
     *
     * @param string $username
     * @param string $id
     * @param string $userType
     * @param array  $roles
     */
    public function __construct(
        $username,
        $id = null,
        $userType = null,
        array $roles = []
    ) {
        $this->username = $username;
        $this->id = $id;
        $this->userType = $userType;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromPayload($username, array $payload)
    {
        /* Two factor authentication */
        if (isset($payload['tfa']) && !$payload['tfa']) {
            throw new NotAcceptableHttpException('two_factor_authentication_error');
        }

        if (isset($payload['roles'])) {
            return new self(
                $username,
                isset($payload['id']) ? (string) $payload['id'] : null,
                isset($payload['user_type']) ? (string) $payload['user_type'] : null,
                (array) $payload['roles']
            );
        }

        return new self($username);
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * Get id
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }
}
