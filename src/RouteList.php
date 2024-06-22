<?php
namespace Aembler\Resources;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;
class RouteList implements RouteListInterface
{
	public function loadRoutes($router)
	{
		$router->buildGroup()
		->routes('routes.php', 'resource_library');
	}
}