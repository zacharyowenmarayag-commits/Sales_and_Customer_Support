<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoutePagesTest extends TestCase
{
    public function test_dashboard_routes_are_defined_once_and_use_the_dashboard_controller(): void
    {
        $routes = collect(app('router')->getRoutes()->getRoutes());

        $homeRoutes = $routes->filter(fn ($route) => $route->uri() === '/');
        $this->assertCount(1, $homeRoutes);
        $this->assertSame('App\\Http\\Controllers\\DashboardController@index', $homeRoutes->first()->getActionName());

        $sprfRoute = $routes->first(fn ($route) => $route->uri() === 'SPRF');
        $this->assertNotNull($sprfRoute);
        $this->assertSame('App\\Http\\Controllers\\DashboardController@sprf', $sprfRoute->getActionName());
        $this->assertTrue(view()->exists('SPRF.index'));

        $somRoute = $routes->first(fn ($route) => $route->uri() === 'som');
        $this->assertNotNull($somRoute);
        $this->assertSame('App\\Http\\Controllers\\DashboardController@som', $somRoute->getActionName());
    }
}
