<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

/**
 * @package icanhazstring\Expressive\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class OptimusConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            /**
             * Default list of attributes to decode.
             *
             * To overwrite them simply provide your application config
             * with the OptimusMiddleware::CONFIG_KEY with a custom array.
             */
            OptimusMiddleware::CONFIG_KEY => ['id'],
        ];
    }

    public function getDependencies()
    {
        return [
            'factories' => [
                OptimusMiddleware::class => OptimusMiddlewareFactory::class
            ]
        ];
    }
}
