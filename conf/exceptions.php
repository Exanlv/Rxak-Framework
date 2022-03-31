<?php

use Rxak\Framework\Exception\SafeException;

return [
    /**
     * Thrown when user input failed
     */
    '400' => new SafeException(403, 'Bad request.'),

    /**
     * Thrown when the authorized check for a validator fails
     */
    '403' => new SafeException(403, 'Forbidden.'),

    /**
     * Thrown when a page is not found
     */
    '404' => new SafeException(404, 'Not found.'),

    /**
     * Thrown when an HTTP method not allowed by the route is used
     */
    '405' => new SafeException(405, 'Method not allowed.'),

    /**
     * Thrown when the user attempts to set coffee using the teapot
     */
    '418' => new SafeException(418, 'I\'m a teapot.'),

    /**
     * Thrown when a client fails the CSRF checks. Unofficial use, adopted from Laravel
     */
    '419' => new SafeException(419, 'Page expired.'),

    /**
     * Thrown when the method specified in a route is not implemented
     */
    '501' => new SafeException(501, 'Not implemented.'),
];