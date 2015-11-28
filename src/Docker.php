<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:39 AM
 */

namespace mglaman\Docker;

/**
 * Class Docker
 * @package mglaman\Docker
 */
class Docker extends DockerBase
{

    /**
     * {@inheritdoc}
     */
    public static function command()
    {
        return 'docker';
    }

    /**
     * @return bool
     */
    public static function exists()
    {
        return self::runCommand('-v')->isSuccessful();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function available()
    {
        return self::runCommand('ps')->isSuccessful();
    }

    /**
     * @param $name
     * @param $port
     * @param string $protocol
     *
     * @return string
     */
    public static function getContainerPort($name, $port, $protocol = 'tcp')
    {
        // Easier to run this than dig through JSON object.
        $cmd = self::runCommand('inspect', [
            "--format='{{(index (index .NetworkSettings.Ports \"{$port}/{$protocol}\") 0).HostPort}}",
            $name
        ]);

        return preg_replace('/[^0-9,.]+/i', '', $cmd->getOutput());
    }

    /**
     * Run a command in a new container.
     *
     * @param array $args
     * @param null $callback
     * @return \Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function run(array $args, $callback = null)
    {
        return self::runCommand('run', $args, $callback);
    }

    /**
     * Start one or more stopped containers.
     *
     * @param array $args
     * @param null $callback
     * @return \Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function start(array $args, $callback = null)
    {
        return self::runCommand('start', $args, $callback);
    }

    /**
     * Stop a running container.
     *
     * @param array $args
     * @param null $callback
     * @return \Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function stop(array $args, $callback = null)
    {
        return self::runCommand('stop', $args, $callback);
    }

    /**
     * Return low-level information on a container or image.
     *
     * @param array $args
     * @param bool|false $raw
     * @param null $callback
     * @return mixed|\Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function inspect(array $args, $raw = false, $callback = null)
    {
        $process = self::runCommand('inspect', $args, $callback);

        if ($process->isSuccessful() && !$raw) {
            $decoded = json_decode($process->getOutput());
            return reset($decoded);
        }

        return $process;
    }

}
