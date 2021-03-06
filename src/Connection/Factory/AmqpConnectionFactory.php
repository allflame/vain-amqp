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

namespace Vain\Amqp\Connection\Factory;

use Vain\Core\Connection\ConnectionInterface;
use Vain\Core\Connection\Factory\AbstractConnectionFactory;

/**
 * Class AmqpConnectionFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class AmqpConnectionFactory extends AbstractConnectionFactory
{
    /**
     * @inheritDoc
     */
    public function createConnection(array $config) : ConnectionInterface
    {

    }
}