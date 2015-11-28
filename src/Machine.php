<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:39 AM
 */

namespace mglaman\Docker;

/**
 * Class Machine
 * @package mglaman\Docker
 */
class Machine extends DockerBase
{
    const RUNNING = 'Running';
    const STOPPED = 'Stopped';

    /**
     * {@inheritdoc}
     */
    public static function command()
    {
        return 'docker-machine';
    }

    /**
     * Checks if the docker-machine command exists.
     *
     * @return bool
     */
    public static function exists()
    {
        return self::runCommand('-v')->isSuccessful();
    }

    /**
     * Start a machine.
     *
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public static function start($name)
    {
        // Check if machine is running.
        $status = self::status($name);

        if ($status == self::STOPPED) {
            // If not, try to start it.
            return self::runCommand('start', [$name])->isSuccessful();
        }

        return $status == Machine::RUNNING;
    }

    /**
     * Stop a machine.
     *
     * @param $name
     * @return \Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function stop($name)
    {
        return self::runCommand('stop', [$name]);
    }

    /**
     * Get the status of a machine.
     *
     * @param $name
     * @return \Symfony\Component\Process\Process
     * @throws \Exception
     */
    public static function status($name)
    {
        return trim(self::runCommand('status', [$name])->getOutput());
    }

    /**
     * Returns an array of the environment for the Docker client.
     *
     * @param $name
     * @return array
     * @throws \Exception
     */
    public static function getEnv($name)
    {
        $envs = [];
        $output = self::runCommand('env', [$name]);
        if (trim($output->getOutput()) == "$name is not running. Please start this with docker-machine start $name") {
            throw new \Exception('Docker machine has not been started yet.');
        }
        $envOutput = explode(PHP_EOL, $output->getOutput());
        foreach ($envOutput as $line) {
            if (strpos($line, 'export') !== false) {
                list(, $export) = explode(' ', $line, 2);
                list($key, $value) = explode('=', $export, 2);
                $envs[$key] = str_replace('"', '', $value);
            }
        }
        return $envs;
    }

    /**
     * Checks if the environment information for Docker client exported.
     * @return bool
     */
    public static function isExported()
    {
        return self::native() || ((getenv('DOCKER_MACHINE_NAME') && getenv('DOCKER_HOST') && getenv('DOCKER_CERT_PATH')));
    }
}
