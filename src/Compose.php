<?php
/**
 * Created by PhpStorm.
 * User: mglaman
 * Date: 9/2/15
 * Time: 12:40 AM
 */

namespace mglaman\Docker;


class Compose extends DockerBase {

  public static function command() {
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
    return self::runCommand('docker-compose', ['-v'])->isSuccessful();
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


  public static function build()
  {
    return self::runCommand('docker-compose', ['build']);
  }

  public static function logs(array $args = [])
  {
    array_unshift($args, 'logs');
    return self::runCommand('docker-compose', $args);
  }

  public static function kill()
  {
    return self::runCommand('docker-compose', ['kill']);
  }

  public static function rm($force = false)
  {
    $args = ['rm'];
    if ($force) {
      $args[] = '-f';
    }
    return self::runCommand('docker-compose', $args);
  }

  public static function start()
  {
    return self::runCommand('docker-compose', ['start']);
  }

  public static function stop()
  {
    return self::runCommand('docker-compose', ['stop']);
  }

  public static function up()
  {
    return self::runCommand('docker-compose', ['up']);
  }

}
