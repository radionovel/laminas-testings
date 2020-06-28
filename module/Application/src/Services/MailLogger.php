<?php

namespace Application\Services;

use Application\Services\Interfaces\Logger;

/**
 * Class MailLogger
 *
 * @package Application\Services
 */
class MailLogger implements Logger
{
    /**
     * @param $message
     *
     * @return mixed|void
     */
    public function log($message)
    {
        var_dump('Send to email: ' . $message);
    }
}
