<?php
declare(strict_types=1);

namespace icanhaztests\Middleware\Unit;

use icanhazstring\Middleware\OptimusMiddleware;
use Jenssegers\Optimus\Optimus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\ServerRequest;

/**
 * @package icanhaztests\Expressive\Middleware\Unit
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class OptimusMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDecodeAttributes()
    {
        $test = $this;

        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::any())->will(function ($args) use ($test) {

            /** @var ServerRequestInterface $request */
            $request = $args[0];
            $test->assertSame(123, $request->getAttribute('id'));

            return new EmptyResponse();
        });


        $optimus = new Optimus(961748941, 61269253, 822583886);
        $middleware = new OptimusMiddleware($optimus, ['id']);

        $request = (new ServerRequest())->withAttribute('id', $optimus->encode(123));

        $middleware->process($request, $handler->reveal());
    }

    /**
     * @test
     */
    public function itShouldNotTouchNonConfiguredAttributes()
    {
        $test = $this;

        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::any())->will(function ($args) use ($test) {

            /** @var ServerRequestInterface $request */
            $request = $args[0];
            $test->assertSame(123, $request->getAttribute('id'));
            $test->assertSame(456, $request->getAttribute('test'));

            return new EmptyResponse();
        });

        $optimus = new Optimus(961748941, 61269253, 822583886);
        $middleware = new OptimusMiddleware($optimus, ['test']);

        $request = (new ServerRequest())
            ->withAttribute('id', 123)
            ->withAttribute('test', $optimus->encode(456));

        $middleware->process($request, $handler->reveal());
    }
}
