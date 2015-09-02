<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:39 AM
 */

namespace mglaman\Docker;


class Docker extends DockerBase {
  public static function command() {
    return 'docker';
  }

  /**
   * @return bool
   */
  public static function exists()
  {
    return self::runCommand('docker', ['-v'])->isSuccessful();
  }

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

  public static function run(array $args, $callback = null) {
    return self::runCommand('run', $args, $callback);
  }

  public static function start(array $args, $callback = null) {
    return self::runCommand('start', $args, $callback);
  }

  public static function stop(array $args, $callback = null) {
    return self::runCommand('stop', $args, $callback);
  }

  public static function inspect(array $args, $raw = false, $callback = null) {
    $process = self::runCommand('inspect', $args, $callback);

    if ($process->isSuccessful() && !$raw) {
      $decoded = json_decode($process->getOutput());
      return reset($decoded);
    }

    return $process;
  }

}
