<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:40 AM
 */

namespace mglaman\Docker;


class Compose extends DockerBase
{

    /**
     * {@inheritdoc}
     */
    public static function command()
    {
        return 'docker-compose';
    }

    /**
     * @param $base
     * @param $type
     *
     * @return string
     */
    public static function getContainerName($base, $type)
    {
        $projectName = str_replace(array('-', '.'), '', $base);
        return $projectName . '_' . $type . '_1';
    }

    /**
     * @return bool
     */
    public static function exists()
    {
        return self::runCommand('-v')->isSuccessful();
    }

    /**
     * @param null $dir
     *
     * @return bool
     */
    public static function configExists($dir = null)
    {
        $dir = ($dir === null) ? getcwd() : $dir;
        return file_exists($dir . '/docker-compose.yml');
    }

    /**
     * Build or rebuild services.
     *
     * @return mixed
     * @throws \Exception
     */
    public static function build()
    {
        return self::runCommand('build');
    }

    /**
     * Kill containers.
     *
     * @return mixed
     * @throws \Exception
     */
    public static function kill()
    {
        return self::runCommand('kill');
    }

    /**
     * View output from containers.
     *
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public static function logs(array $args = [])
    {
        return self::runCommand('logs', $args);
    }

    /**
     * Pause services.
     *
     * @return mixed
     * @throws \Exception
     */
    public static function pause()
    {
        return self::runCommand('pause');
    }

    /**
     * Print the public port for a port binding.
     *
     * @param $service
     * @param $privatePort
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public static function port($service, $privatePort, array $args = [])
    {
        $args[] = $service;
        $args[] = $privatePort;
        return self::runCommand('port', $args);
    }

    /**
     * Restart services.
     *
     * @param int $timeout
     * @param array $services
     * @return mixed
     * @throws \Exception
     */
    public static function restart($timeout = 10, array $services = [])
    {
        $args = [
                '-t ' . $timeout,
            ] + $services;
        return self::runCommand('restart', $args);
    }

    /**
     * Remove stopped containers.
     *
     * @param bool|false $force
     * @return mixed
     * @throws \Exception
     */
    public static function rm($force = false)
    {
        $args = [];
        if ($force) {
            $args[] = '-f';
        }
        return self::runCommand('rm', $args);
    }

    /**
     * Start services.
     *
     * @return mixed
     * @throws \Exception
     */
    public static function start()
    {
        return self::runCommand('start');
    }

    /**
     * Stop services.
     *
     * @return mixed
     * @throws \Exception
     */
    public static function stop()
    {
        return self::runCommand('stop');
    }

    /**
     * Create and start containers.
     *
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public static function up(array $args = [])
    {
        return self::runCommand('up', $args);
    }

}
