<?php declare(strict_types=1);

namespace SlimFacades\Http;

use SlimFacades\Facades\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @param string $content
     * @param integer $statusCode
     */
    public function __construct(protected $content = '', protected $statusCode = 200)
    {
        $response = ResponseFactory::createResponse();

        $response->getBody()->write($this->content);
        $this->response = $response->withStatus($this->statusCode);
    }

    /**
     * Get the Response
     *
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Make a json response
     *
     * @param array $data
     * @return self
     */
    public function json(array $data): self
    {
        $payload = json_encode($data);
        $response = $this->get();
        $response->getBody()->write($payload);
        
        $this->response = $response->withHeader('Content-Type', 'application/json');

        return $this;
    }
}
