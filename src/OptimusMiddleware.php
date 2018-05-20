<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

use Jenssegers\Optimus\Optimus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function is_numeric;

/**
 * OptimusMiddleware
 *
 * Takes configured keys from the request and decodes them.
 *
 * @package icanhazstring\Expressive\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class OptimusMiddleware implements MiddlewareInterface
{
    public const CONFIG_KEY = 'optimus_attributes';

    /** @var Optimus */
    private $optimus;
    /** @var array */
    private $attributes;

    /**
     * @param Optimus $optimus
     * @param array   $attributes
     */
    public function __construct(Optimus $optimus, array $attributes)
    {
        $this->optimus = $optimus;
        $this->attributes = $attributes;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestAttributes = $request->getAttributes();

        foreach ($this->attributes as $attribute) {
            if (!isset($requestAttributes[$attribute])) {
                continue;
            }

            $attributeValue = $requestAttributes[$attribute];

            if (!is_numeric($attributeValue)) {
                continue;
            }

            $request = $request->withAttribute($attribute, $this->optimus->decode((int)$attributeValue));
        }

        return $handler->handle($request);
    }
}
