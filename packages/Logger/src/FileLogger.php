<?php

namespace Logger;

use Logger\Interfaces\Logger;

/**
 * Class MailLogger
 *
 * @package Application\Services
 */
class FileLogger implements Logger
{
    /**
     * @param $message
     *
     * @return mixed|void
     */
    public function log($message)
    {
        var_dump('Write to file: ' . $message);
    }
}
