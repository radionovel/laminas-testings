<?php


namespace Application\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class Application2Middleware implements MiddlewareInterface
{
    public function __invoke($foo, $bar, $bat = null)
    {
        $f = 123;
        $f = 1232;
        $f2 = 1232;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $f = 123;
        $f = 1232;
        $f2 = 1232;

        return null;

//        $response = new \Laminas\Http\Response();
//        return $response->setStatusCode(200);


        return $handler->handle($request);
    }

}