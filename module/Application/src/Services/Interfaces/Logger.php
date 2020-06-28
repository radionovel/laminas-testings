<?php

namespace Application\Services\Interfaces;

interface Logger
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function log($message);
}
