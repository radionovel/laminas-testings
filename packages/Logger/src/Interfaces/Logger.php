<?php

namespace Logger\Interfaces;

interface Logger
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function log($message);
}
