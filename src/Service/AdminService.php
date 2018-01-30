<?php

namespace Garlic\Auth\Service;

use Garlic\Bus\Service\CommunicatorService;
use Garlic\Bus\Entity\Response;

/**
 * Class AdminService
 */
class AdminService
{
    /** @var CommunicatorService */
    private $communicator;

    /** @var int */
    protected $ttl;

    /** @var string */
    protected $adminUser;

    /** @var string */
    protected $adminPassword;

    /**
     * AdminService constructor.
     *
     * @param CommunicatorService $communicator
     */
    public function __construct(CommunicatorService $communicator)
    {
        $this->communicator = $communicator;
        $this->adminUser = getenv('USER_ADMIN_USERNAME');
        $this->adminPassword = getenv('USER_ADMIN_PASSWORD');
    }

    /**
     * Get admin token
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getAdminToken()
    {
        /** @var Response $response */
        $response = $this->communicator
            ->request(getenv('USER_SERVICE_NAME'))
            ->post()
            ->send(
                'login_check',
                [],
                [
                    '_username' => $this->adminUser,
                    '_password' => $this->adminPassword,
                ]
            )
            ->getJsonResponse();

        $data = json_decode($response->getContent(), true);

        if (isset($data['token'])) {
            return $data['token'];
        }

        throw new \Exception('Administrator token has not received');
    }
}
