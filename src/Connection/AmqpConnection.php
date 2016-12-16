<?php
/**
 * Vain Framework
 *
 * PHP Version 7
 *
 * @package   vain-amqp
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/allflame/vain-amqp
 */

namespace Vain\Amqp\Connection;

use Vain\Core\Connection\AbstractConnection;

/**
 * Class AmqpConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 *
 * @method \AMQPConnection establish
 */
class AmqpConnection extends AbstractConnection
{
    /**
     * @inheritDoc
     */
    protected function getCredentials(array $configData) : array
    {
        $host = $configData['host'];
        $port = $configData['port'];
        $login = $configData['login'];
        $password = $configData['password'];

        return [
            $host,
            $port,
            $login,
            $password
        ];
    }

    /**
     * @inheritDoc
     */
    public function doConnect(array $configData)
    {
        list (
            $host,
            $port,
            $login,
            $password,
            ) = $this->getCredentials($configData);

        $amqpConnection = new \AMQPConnection(['host' => $host, 'port' => $port, 'login' => $login, 'password' => $password]);
        $amqpConnection->connect();

        return $amqpConnection;
    }
}