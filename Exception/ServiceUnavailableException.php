<?php

namespace Ady\Bundle\MaintenanceBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The server is currently unavailable (because it is overloaded or down for maintenance).
 *
 * @author  Gilles Gauthier <g.gauthier@lexik.fr>
 */
class ServiceUnavailableException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string          $message  The internal exception message
     * @param \Exception|null $previous The previous exception
     * @param int             $code     The internal exception code
     */
    public function __construct($message = '', \Exception $previous = null, $code = 0)
    {
        parent::__construct(503, $message, $previous, [], $code);
    }
}
