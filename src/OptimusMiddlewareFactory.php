<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

use Jenssegers\Optimus\Optimus;
use Psr\Container\ContainerInterface;
use function sprintf;

/**
 * @package icanhazstring\Expressive\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class OptimusMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return OptimusMiddleware
     */
    public function __invoke(ContainerInterface $container): OptimusMiddleware
    {
        if (!$container->has(Optimus::class)) {
            throw new Exception\InvalidConfigException(sprintf(
                'Cannot create %s service; dependency %s is missing',
                OptimusMiddleware::class,
                Optimus::class
            ));
        }

        $attributes = $container->get('config')[OptimusMiddleware::CONFIG_KEY] ?? null;

        if (!$attributes) {
            throw new Exception\InvalidConfigException(sprintf(
                'Missing key %s in application config. ConfigProvider included in application config?',
                OptimusMiddleware::CONFIG_KEY
            ));
        }

        return new OptimusMiddleware($container->get(Optimus::class), $attributes);
    }
}
