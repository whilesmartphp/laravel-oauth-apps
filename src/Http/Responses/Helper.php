<?php

namespace Whilesmart\LaravelAppAuthentication\Http\Responses;

use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Spatie\Fractalistic\ArraySerializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait Helper
{
    /**
     * Respond with a success response and associate a location if provided.
     *
     * @param  null|string  $location
     * @return Response
     */
    public function success($location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(200);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param  null|string  $location
     * @return Response
     */
    public function created($location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(201);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param  mixed  $content
     * @return Response
     */
    public function accepted(?string $location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(202);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @return Response
     */
    public function noContent()
    {
        $response = new Response(null);

        return $response->setStatusCode(204);
    }

    /**
     * Bind a collection to a transformer and start building a response.
     *
     * @param  string|callable|object  $transformer
     * @param  array|Closure  $parameters
     * @return Response
     */
    public function collection(Collection $collection, $transformer = null, $includes = [], ?Closure $after = null)
    {
        return new Response(fractal()
            ->collection($collection, $transformer)
            ->parseIncludes($includes)
            ->serializeWith(new ArraySerializer)
            ->toArray(), 200);
    }

    /**
     * Bind an item to a transformer and start building a response.
     *
     * @param  object  $item
     * @param  null|string|callable|object  $transformer
     * @param  array  $parameters
     * @return Response
     */
    public function item($item, $transformer = null, $includes = [], ?Closure $after = null)
    {
        return new Response(
            fractal()
                ->item(
                    $item,
                    $transformer
                )
                ->parseIncludes($includes)
                ->toArray(),
            200,
            [],
        );
    }

    /**
     * Bind an arbitrary array to a transformer and start building a response.
     *
     * @param  array  $parameters
     * @return Response
     */
    public function array(array $array, $transformer = null, $parameters = [], ?Closure $after = null)
    {
        return new Response(fractal()
            ->collection($array, $transformer)
            ->serializeWith(new ArraySerializer)
            ->toArray(),
            200
        );
    }

    /**
     * Bind a paginator to a transformer and start building a response.
     *
     * @param  Paginator  $paginator
     * @param  null|string|callable|object  $transformer
     * @return Response
     */
    public function paginator(LengthAwarePaginator $paginator, $transformer = null, array $includes = [], ?Closure $after = null)
    {
        $items = $paginator->getCollection();

        return new Response(fractal()
            ->collection($items, $transformer)
            ->parseIncludes($includes)
//            ->serializeWith(new AppJsonSerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray(), 200);
    }

    /**
     * Return a 404 not found error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }

    /**
     * Return an error response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     *
     * @throws HttpException
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorForbidden($message = 'Forbidden')
    {
        $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->error($message, 401);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param  string  $message
     *
     * @throws HttpException
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->error($message, 405);
    }
}
