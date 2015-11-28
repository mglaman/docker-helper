<?php

namespace mglaman\Docker;

/**
 * Interface DockerInterface
 * @package mglaman\Docker
 */
interface DockerInterface {

    /**
     * Returns the command to be run.
     *
     * @return string
     */
    public static function command();
}