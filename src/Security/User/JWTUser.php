<?php

namespace AuthorizationBundle\Security\User;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

/**
 * Class JWTUser
 */
class JWTUser implements JWTUserInterface
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
    private $fullName;

    /**
     * @var string
     */
    private $btcAddress;

    /**
     * @var string
     */
    private $ethAddress;

    /**
     * @var string
     */
    private $eth;

    /**
     * JWTUser constructor.
     *
     * @param string $username
     * @param string $id
     * @param string $fullName
     * @param string $btcAddress
     * @param string $ethAddress
     * @param string $eth
     * @param array  $roles
     */
    public function __construct(
        $username,
        $id = null,
        $fullName = null,
        $btcAddress = null,
        $ethAddress = null,
        $eth = null,
        array $roles = []
    ) {
        $this->username = $username;
        $this->id = $id;
        $this->fullName = $fullName;
        $this->btcAddress = $btcAddress;
        $this->ethAddress = $ethAddress;
        $this->eth = $eth;
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
                isset($payload['full_name']) ? (string) $payload['full_name'] : null,
                isset($payload['btc_address']) ? (string) $payload['btc_address'] : null,
                isset($payload['eth_address']) ? (string) $payload['eth_address'] : null,
                isset($payload['eth']) ? (string) $payload['eth'] : null,
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
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Get btc address
     *
     * @return string
     */
    public function getBtcAddress()
    {
        return $this->btcAddress;
    }

    /**
     * Get eth address
     *
     * @return string
     */
    public function getEthAddress()
    {
        return $this->ethAddress;
    }

    /**
     * Get eth
     *
     * @return string
     */
    public function getEth()
    {
        return $this->eth;
    }
}
