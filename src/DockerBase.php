<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:41 AM
 */

namespace mglaman\Docker;


use Symfony\Component\Process\ProcessBuilder;

interface DockerInterface {
  /**
   * Returns the command to be run.
   *
   * @return string
   */
  public static function command();
}

abstract class DockerBase implements DockerInterface {

  /**
   * @return bool
   */
  public static function native() {
    return PHP_OS == 'Linux';
  }

  protected static function runCommand($command, $args = [], $callback = null)
  {
    // Place command before args
    array_unshift($args, $command);

    // Place docker/docker-compose/etc before command.
    /** @var DockerInterface $calledClass */
    $calledClass = get_called_class();
    array_unshift($args, $calledClass::command());

    $processBuilder = ProcessBuilder::create($args);
    $processBuilder->setTimeout(3600);

    // Set environment variables. May have been defined with ::dockerMachineEnvironment
    // and not the parent process.
    if (!self::native()) {
      $processBuilder->setEnv('DOCKER_TLS_VERIFY', 1);
      $processBuilder->setEnv('DOCKER_MACHINE_NAME', getenv('DOCKER_MACHINE_NAME'));
      $processBuilder->setEnv('DOCKER_HOST', getenv('DOCKER_HOST'));
      $processBuilder->setEnv('DOCKER_CERT_PATH', getenv('DOCKER_CERT_PATH'));
    }

    $process = $processBuilder->getProcess();

    $process->run($callback);
    if (!$process->isSuccessful()) {
      throw new \Exception('Error executing docker command');
    }

    return $process;
  }
}
