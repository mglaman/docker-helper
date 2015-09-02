<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:39 AM
 */

namespace mglaman\Docker;

class DockerCompose extends DockerBase {
  function command() {
    return 'docker-machine';
  }

  /**
   * @return bool
   */
  public static function exists()
  {
    return self::runCommand('docker-machine', ['-v'])->isSuccessful();
  }

  public static function start($name)
  {
    // Check if machine is running.
    $process = self::runCommand('status', [$name]);

    if (trim($process->getOutput()) == 'Stopped') {
      // If not, try to start it.
      return self::runCommand('start', [$name])->isSuccessful();
    }

    return (trim($process->getOutput()) == trim('Running'));
  }

  public static function stop($name)
  {
    return self::runCommand('stop', [$name]);
  }

  public static function getEnv($name)
  {
    $envs = [];
    $output = self::runCommand('env', [$name]);
    if (trim($output->getOutput()) == "$name is not running. Please start this with docker-machine start $name") {
      throw new \Exception('Docker machine has not been started yet..');
    }
    $envOutput = explode(PHP_EOL, $output->getOutput());
    foreach ($envOutput as $line) {
      if (strpos($line, 'export') !== false) {
        list($cmd, $export) = explode(' ', $line, 2);
        list($key, $value) = explode('=', $export, 2);
        $envs[$key] = str_replace('"', '', $value);
      }
    }
    return $envs;
  }

  public static function isExported()
  {
    return self::native() || ((getenv('DOCKER_MACHINE_NAME') && getenv('DOCKER_HOST') && getenv('DOCKER_CERT_PATH')));
  }
}
