<?php

namespace Illuminate\Http\Middleware;

use Closure;
use Fruitcake\Cors\CorsService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

class HandleCors
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The CORS service instance.
     *
     * @var \Fruitcake\Cors\CorsService
     */
    protected $cors;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @param  \Fruitcake\Cors\CorsService  $cors
     * @return void
     */
    public function __construct(Container $container, CorsService $cors)
    {
        $this->container = $container;
        $this->cors = $cors;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        // Verifica si la solicitud proviene del origen permitido
        if (! $this->isAllowedOrigin($request)) {
            return $next($request);
        }

        // Configura las opciones de CORS específicas para la ruta http://localhost:5173
        $this->cors->setOptions([
            'allowed_origins' => ['http://localhost:5173'], // Solo permitir este origen
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // Métodos permitidos
            'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'], // Encabezados permitidos
            'exposed_headers' => ['Authorization'], // Encabezados expuestos
            'max_age' => 3600, // Tiempo de cache de preflight
            'supports_credentials' => true, // Permitir cookies y credenciales
        ]);

        if ($this->cors->isPreflightRequest($request)) {
            $response = $this->cors->handlePreflightRequest($request);
            $this->cors->varyHeader($response, 'Access-Control-Request-Method');
            return $response;
        }

        $response = $next($request);

        if ($request->getMethod() === 'OPTIONS') {
            $this->cors->varyHeader($response, 'Access-Control-Request-Method');
        }

        return $this->cors->addActualRequestHeaders($response, $request);
    }

    /**
     * Check if the request comes from an allowed origin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isAllowedOrigin(Request $request): bool
    {
        return $request->header('Origin') === 'http://localhost:5173';
    }

    /**
     * Get the CORS paths for the given host.
     *
     * @param  string  $host
     * @return array
     */
    protected function getPathsByHost(string $host)
    {
        $paths = $this->container['config']->get('cors.paths', []);

        if (isset($paths[$host])) {
            return $paths[$host];
        }

        return array_filter($paths, function ($path) {
            return is_string($path);
        });
    }
}
