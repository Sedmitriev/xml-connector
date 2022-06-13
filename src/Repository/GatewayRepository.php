<?php

declare(strict_types=1);

namespace Connector\Repository;

use Doctrine\DBAL\Connection;

abstract class GatewayRepository
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * GatewayRepository constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->connection = $conn;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
