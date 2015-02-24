<?php
/**
 * built-in Server Shell
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.3.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses( 'AppShell', 'Console/Command' );

/**
 * Init Shell
 * CLI app to basically create a new random salt and cipher seed for a new application
 * You should re-create the administrator passwords after doing this
 *
 * @package       Cake.Console.Command
 */
class InitShell extends AppShell
{
  /**
   * document root
   *
   * @var string
   */
  protected $_documentRoot = NULL;


  /**
   * Override initialize of the Shell
   *
   * @return void
   */
  public function initialize()
  {
    $this->_documentRoot = WWW_ROOT;
  }


  /**
   * Starts up the Shell and displays the welcome message.
   * Allows for checking and configuring prior to command or main execution
   *
   * Override this method if you want to remove the welcome information,
   * or otherwise modify the pre-command flow.
   *
   * @return void
   * @link http://book.cakephp.org/2.0/en/console-and-shells.html#Shell::startup
   */
  public function startup()
  {

    parent::startup();
  }


  /**
   * Displays a header for the shell
   *
   * @return void
   */
  protected function _welcome()
  {
    $this->out();
    $this->out( __d( 'cake_console', '<info>Welcome to CakePHP %s Console</info>', 'v' . Configure::version() ) );
    $this->hr();
    $this->out( __d( 'cake_console', 'App : %s', APP_DIR ) );
    $this->out( __d( 'cake_console', 'Path: %s', APP ) );
    $this->out( __d( 'cake_console', 'DocumentRoot: %s', $this->_documentRoot ) );
    $this->hr();
  }


  /**
   * Override main() to handle action
   *
   * @return void
   */
  public function main()
  {
    $init = $this->in( 'Initialize Application?', array( 'y', 'n' ), 'n' );

    if ( strtolower( $init ) == 'y' )
    {
      $name = $this->in( 'Please provide a short name for the application', NULL );

      $password = $this->in( 'Please provide a new password for the \'admin\' user', NULL );

      //@todo poner alguna validacion a estos pasos

      $cookie = Inflector::slug( $name );
      $prefix = $cookie . '_';
      $newSalt = PasswordGenerator::getCustomPassword( str_split( "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%&*" ), 40 );
      $newCipher = PasswordGenerator::getCustomPassword( str_split( "1234567890" ), 29 );

      App::uses( 'File', 'Utility' );

      $core = new File( APP . 'Config' . DS . 'core.php' );
      $coreText = $core->read();

      $coreText = preg_replace( '/Configure::write\(\s*\'Security.salt\'\s*,\s*\'.+?\'\s*\)\s*;/', "Configure::write( 'Security.salt', '{$newSalt}' );", $coreText );
      $coreText = preg_replace( '/Configure::write\(\s*\'Security.cipherSeed\'\s*,\s*\'.+?\'\s*\)\s*;/', "Configure::write( 'Security.cipherSeed', '{$newCipher}' );", $coreText );
      $coreText = preg_replace( "/'cookie'\s*=>\s*'.+?'\s*,/", "'cookie' => '{$cookie}',", $coreText );
      $coreText = preg_replace( "/\\\$prefix\s*=\s*'.+?';/", "\$prefix = '{$prefix}';", $coreText );

      $core->write( $coreText );

      $admin = new File( APP . 'Config' . DS . 'data' . DS . 'administrators.php' );
      $adminText = $admin->read();

      $hashedPassword = Security::hash( $password, NULL, $newSalt );
      $adminText = preg_replace( "/'password'\s*=>\s*'[a-f0-9]+',/m", "'password' => '{$hashedPassword}',", $adminText );

      $admin->write( $adminText );

      $settings = new File( APP . 'Config' . DS . 'data' . DS . 'settings.php' );
      $settingsText = $settings->read();

      $override_cookie = "override_{$cookie}";
      $settingsText = preg_replace( "/\\\$_COOKIE\[\s*'.+?'\s*\]\[.+?\]/m", "\$_COOKIE[ '{$override_cookie}' ][ '{$newCipher}' ]", $settingsText );

      $settings->write( $settingsText );

      $this->out( 'New Random salt and cipherSeed Generated, custom name added, and new password created for the administrator!' );
    }
    else
    {
      $this->out( 'Nothing changed' );
    }
  }


  /**
   * Get and configure the optionparser.
   *
   * @return ConsoleOptionParser
   */
  public function getOptionParser()
  {
    $parser = parent::getOptionParser();

    $parser->description( array(
      __d( 'cake_console', 'Re-creates Security.salt and Security.cipherSeed in core.php' ),
      __d( 'cake_console', 'Should be called ONCE at the begining of each new project' ),
      __d( 'cake_console', 'Please re-create administrators passwords afterwards' ),
    ) );

    return $parser;
  }
}

/*
 * Unbiased random password generator.
 * This code is placed into the public domain by Defuse Security.
 * WWW: https://defuse.ca/
 */
class PasswordGenerator
{
  public static function getASCIIPassword( $length )
  {
    $printable = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";

    return self::getCustomPassword( str_split( $printable ), $length );
  }


  public static function getAlphaNumericPassword( $length )
  {
    $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    return self::getCustomPassword( str_split( $alphanum ), $length );
  }


  public static function getHexPassword( $length )
  {
    $hex = "0123456789ABCDEF";

    return self::getCustomPassword( str_split( $hex ), $length );
  }


  /*
   * Create a random password composed of a custom character set.
   * $characterSet - An *array* of strings the password can be composed of.
   * $length - The number of random strings (in $characterSet) to include in the password.
   * Returns false on error (always check!).
   */
  public static function getCustomPassword( $characterSet, $length )
  {
    if ( $length < 1 || !is_array( $characterSet ) )
      return FALSE;

    $charSetLen = count( $characterSet );
    if ( $charSetLen == 0 )
      return FALSE;

    $random = self::getRandomInts( $length * 2 );
    $mask = self::getMinimalBitMask( $charSetLen - 1 );

    $password = "";

    // To generate the password, we repeatedly try random integers and use the ones within the range
    // 0 to $charSetLen - 1 to select an index into the character set. This is the only known way to
    // make a truly unbiased random selection from a set using random binary data.

    // A poorly implemented or malicious RNG could cause an infinite loop, leading to a denial of service.
    // We need to guarantee termination, so $iterLimit holds the number of further iterations we will allow.
    // It is extremely unlikely (about 2^-64) that more than $length*64 random ints are needed.
    $iterLimit = max( $length, $length * 64 ); // If length is close to PHP_INT_MAX we don't want to overflow.
    $randIdx = 0;
    while ( strlen( $password ) < $length )
    {
      if ( $randIdx >= count( $random ) )
      {
        $random = self::getRandomInts( 2 * ( $length - count( $password ) ) );
        $randIdx = 0;
      }

      // This is wasteful, but RNGs are fast and doing otherwise adds complexity and bias.
      $c = $random[ $randIdx++ ] & $mask;
      // Only use the random number if it is in range, otherwise try another (next iteration).
      if ( $c < $charSetLen )
        $password .= $characterSet[ $c ];

      // Guarantee termination
      $iterLimit--;
      if ( $iterLimit <= 0 )
        return FALSE;
    }

    return $password;
  }


  // Returns the smallest bit mask of all 1s such that ($toRepresent & mask) = $toRepresent.
  // $toRepresent must be an integer greater than or equal to 1.
  private static function getMinimalBitMask( $toRepresent )
  {
    if ( $toRepresent < 1 )
      return FALSE;
    $mask = 0x1;
    while ( $mask < $toRepresent )
      $mask = ( $mask << 1 ) | 1;

    return $mask;
  }


  // Returns an array of $numInts random integers between 0 and PHP_INT_MAX
  private static function getRandomInts( $numInts )
  {
    $rawBinary = mcrypt_create_iv( $numInts * PHP_INT_SIZE, MCRYPT_DEV_URANDOM );
    $ints = array();
    for ( $i = 0; $i < $numInts; $i += PHP_INT_SIZE )
    {
      $thisInt = 0;
      for ( $j = 0; $j < PHP_INT_SIZE; $j++ )
      {
        $thisInt = ( $thisInt << 8 ) | ( ord( $rawBinary[ $i + $j ] ) & 0xFF );
      }
      // Absolute value in two's compliment (with min int going to zero)
      $thisInt = $thisInt & PHP_INT_MAX;
      $ints[ ] = $thisInt;
    }

    return $ints;
  }
}