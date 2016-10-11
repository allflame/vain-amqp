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

use Vain\Connection\AbstractConnection;

/**
 * Class AmqpConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
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
        $exchangeName = '';
        $declareExchange = false;
        $exchangeType = $configData['exchange']['type'];
        $queueName = '';
        $declareQueue = false;
        $queueFlags = AMQP_EXCLUSIVE;

        if (array_key_exists('name', $configData['exchange'])) {
            $exchangeName = $configData['exchange']['name'];
        }

        if (array_key_exists('declare', $configData['exchange'])) {
            $declareExchange = $configData['exchange']['declare'];
        }

        if (array_key_exists('name', $configData['queue'])) {
            $queueName = $configData['queue']['name'];
        }

        if (array_key_exists('declare', $configData['queue'])) {
            $declareQueue = $configData['queue']['declare'];
        }

        if (array_key_exists('flags', $configData['queue'])) {
            $queueFlags = $configData['queue']['flags'];
        }

        return [
            $host,
            $port,
            $login,
            $password,
            $exchangeName,
            $exchangeType,
            $declareExchange,
            $queueName,
            $queueFlags,
            $declareQueue,
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
            $exchangeName,
            $exchangeType,
            $declareExchange,
            $queueName,
            $queueFlags,
            $declareQueue
            ) = $this->getCredentials($configData);

        $amqpConnection = new \AMQPConnection();
        $amqpConnection->connect();
        $channel = new \AMQPChannel($amqpConnection);

        $exchange = new \AMQPExchange($channel);
        $exchange->setType($exchangeType);
        if ($exchangeName) {
            $exchange->setName($exchangeName);
        }

        if ($declareExchange) {
            $exchange->declareExchange();
        }
        $queue = new \AMQPQueue($channel);
        $queue->setFlags($queueFlags);

        if ($queueName) {
            $queue->setName($queueName);
        }

        if ($declareQueue) {
            $queue->declareQueue();
        }

        $queue->bind($exchange->getName(), $queue->getName());

        return $amqpConnection;
    }
}