<?php
declare(strict_types=1);

namespace icanhaztests\Middleware\Unit;

use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Middleware\OptimusMiddleware;
use icanhazstring\Middleware\OptimusMiddlewareFactory;
use Jenssegers\Optimus\Optimus;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function sprintf;

/**
 * @package icanhaztests\Expressive\Middleware\Unit
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class OptimusMiddlewareFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldThrowExceptionOnMissingDependency()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Optimus::class)->shouldBeCalled()->willReturn(false);

        $factory = new OptimusMiddlewareFactory();

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage(sprintf(
            'Cannot create %s service; dependency %s is missing',
            OptimusMiddleware::class,
            Optimus::class
        ));

        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionOnMissingConfiguration()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Optimus::class)->shouldBeCalled()->willReturn(true);
        $container->get('config')->shouldBeCalled()->willReturn([]);

        $factory = new OptimusMiddlewareFactory();

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage(sprintf(
            'Missing key %s in application config. ConfigProvider included in application config?',
            OptimusMiddleware::CONFIG_KEY
        ));

        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldCreateProperMiddlewareInstance()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Optimus::class)->shouldBeCalled()->willReturn(true);
        $container->get('config')->shouldBeCalled()->willReturn([OptimusMiddleware::CONFIG_KEY => ['id']]);
        $container->get(Optimus::class)->shouldBeCalled()->willReturn(new Optimus(0, 0, 0));

        $factory = new OptimusMiddlewareFactory();
        $middleware = $factory($container->reveal());

        self::assertInstanceOf(OptimusMiddleware::class, $middleware);
    }
}
