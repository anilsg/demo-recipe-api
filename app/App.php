<?php
namespace recipe\app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

class App
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args = [])
    {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name<br>");
        return $response;
    }
}
