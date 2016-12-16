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

namespace Vain\Amqp\Queue\Factory;

use Vain\Core\Connection\ConnectionInterface;
use Vain\Core\Queue\Factory\AbstractQueueFactory;

/**
 * Class AmqpQueueFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class AmqpQueueFactory extends AbstractQueueFactory
{
    /**
     * @inheritDoc
     */
    public function createQueue(array $configData, ConnectionInterface $connection)
    {
        trigger_error('Method createQueue is not implemented', E_USER_ERROR);
    }
}