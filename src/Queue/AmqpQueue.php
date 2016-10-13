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

namespace Vain\Amqp\Queue;

use Vain\Amqp\Connection\AmqpConnection;
use Vain\Queue\Message\QueueMessageInterface;
use Vain\Queue\QueueInterface;

/**
 * Class AmqpQueue
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class AmqpQueue implements QueueInterface
{
    private $amqpConnection;

    private $configData;

    /**
     * @var \AMQPExchange
     */
    private $exchange;

    /**
     * @var \AMQPQueue
     */
    private $queue;


    /**
     * AmqpQueue constructor.
     *
     * @param AmqpConnection $amqpConnection
     * @param array          $configData
     */
    public function __construct(AmqpConnection $amqpConnection, array $configData)
    {
        $this->amqpConnection = $amqpConnection;
        $this->configData = $configData;
    }

    /**
     * @inheritDoc
     */
    protected function getCredentials(array $configData) : array
    {
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
    public function subscribe()
    {
        if (null !== $this->queue) {
            return $this;
        }

        list (
            $exchangeName,
            $exchangeType,
            $declareExchange,
            $queueName,
            $queueFlags,
            $declareQueue
            ) = $this->getCredentials($this->configData);

        $this->amqpConnection->establish()->connect();

        $channel = new \AMQPChannel($this->amqpConnection->establish());
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

        $this->exchange = $exchange;
        $this->queue = $queue;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unSubscribe()
    {
        $this->exchange = $this->queue = null;
        $this->amqpConnection->establish()->disconnect();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enqueue(QueueMessageInterface $queueMessage)
    {

    }

    /**
     * @inheritDoc
     */
    public function dequeue() : QueueMessageInterface
    {
        trigger_error('Method dequeue is not implemented', E_USER_ERROR);
    }
}