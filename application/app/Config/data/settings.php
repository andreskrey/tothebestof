<?php
/**
 * Configuración de settings custom para la aplicación
 *
 * La configuración es cargada como modelo de datos, como si fuera una tabla de DDBB
 *
 * FIELDS OBLIGATORIOS:
 * id: usar numeros únicos, que NO SE REPITAN
 * name: Nombre para mostrar en la pantalla de configuración
 * type: boolean, integer, string, select, date, datetime. ver descripción más abajo de cada tipo
 * key: la clave desde donde se va a leer la config, ej: Config.loca se leería: Configure::read('Config.loca')
 * value: valor, si es de solo lectura, pues eso. Si es sobreescribible, será el valor por defecto.
 * overridable: indica si la configuración se puede sobreescribir vía Cookie de usuario
 * inject: indica si la configuración se debe ingresar a Configure independientemente si está o no sobreescrita, generalmente true para overridables custom
 * warn: opcional, en caso de que sea sobre escribible, un mensaje de alerta para mostrar durante sesion sobre escrita
 *
 * TIPOS DE DATOS POSIBLES:
 * boolean: muestra checkbox en form de edit
 * integer: muestra input text en form de edit
 * string: muestra input text, on numeric increment en form de edit
 * select: muestra select, necesita una clave más en la estructura de datos, llamada options, tipo -- 'options' => array('k1' => 'v1', 'k2' => 'v2') --
 * date: muestra date picker en form de edit, conviene poner una fecha (YYYY-mm-dd) default
 * datetime: muestra date time picker en form de edit, conviene poner una fecha (YYYY-mm-dd HH:ii:ss) default
 *
 */
$config = array(
  'SettingsData' => array(
    array(
      'id'          => 10,
      'name'        => 'Environment',
      'description' => 'Entorno actual de la aplicación, generalmente auto-detectado',
      'type'        => 'string',
      'key'         => 'Config.environment',
      'value'       => Configure::read( 'Config.environment' ),
      'overridable' => FALSE,
      'overwritten' => FALSE,
    ),
    array(
      'id'          => 20,
      'name'        => 'Debug Override',
      'description' => 'Permite sobre-escribir el modo Debug, sólo para el usuario logueado',
      'type'        => 'integer',
      'key'         => 'debug',
      'value'       => Configure::read( 'debug' ),
      'overridable' => TRUE,
      'overwritten' => FALSE,
      'warn'        => "Debug está sobre-escrito en tu sesión. La experiencia de usuario promedio puede ser diferente a la actual. Valor real: '%s', actual: '%s'"
    ),
    array(
      'id'          => 30,
      'name'        => 'Disable Cache',
      'description' => 'Deshabilita Cache de datos de la aplicación, forzando a re-pedir cualquier información que de otra forma estaría cacheada en Filesystem, DDBB, Memcache, etc',
      'type'        => 'boolean',
      'key'         => 'Cache.disable',
      'value'       => Configure::read( 'Cache.disable' ),
      'overridable' => TRUE,
      'overwritten' => FALSE,
      'warn'        => "Disable Cache está habilitado en tu sesión. La experiencia de usuario promedio puede ser diferente a la actual."
    ),
    array(
      'id'          => 40,
      'name'        => 'Disable View Cache',
      'description' => 'Deshabilita View Cache, forzando a la aplicación a renderear copias nuevas de páginas que de otra forma serían servidas de versiones ya cacheada. <em>No</em> invalida el Cache existente.',
      'type'        => 'boolean',
      'key'         => 'Cache.check',
      'value'       => Configure::read( 'Cache.check' ),
      'overridable' => TRUE,
      'overwritten' => FALSE,
      'warn'        => "Disable View Cache está sobreescrito en tu sesión. Podrías (aunque no deberías) estar viendo contenido diferente al de un usuario normal."
    ),
    array(
      'id'          => 50,
      'name'        => 'Disable Debug Kit',
      'description' => 'Quita el Panel de Debug Kit; de todas formas también se quita en Debug = 0',
      'type'        => 'boolean',
      'key'         => 'DebugKit.disable',
      'value'       => 0,
      'overridable' => TRUE,
      'overwritten' => FALSE,
    ),
    array(
      'id'          => 60,
      'name'        => 'Disable Asset Compress',
      'description' => 'Deshabilita la concatenación de JS y CSS para facilitar el desarrollo y testeo',
      'type'        => 'boolean',
      'key'         => 'AssetCompress.raw',
      'value'       => Configure::read( 'AssetCompress.raw' ),
      'overridable' => TRUE,
      'overwritten' => FALSE,
    ),
  )
);

/**
 * NO MODIFICAR!!
 * A mano leemos informacion de cookies
 */

//override de settings custom en Cookie
if ( isset( $_COOKIE[ 'override_TTBO' ][ '62257660491156489862673184618' ] ) )
{
  // <-- eliminar si no se usa Rijndael
  $type = Configure::read( 'Security.encryptType' );
  $key = Configure::read( 'Security.salt' );
  $cryptKey = substr( $key, 0, 32 );
  $algorithm = MCRYPT_RIJNDAEL_256;
  $mode = MCRYPT_MODE_CBC;
  $ivSize = mcrypt_get_iv_size( $algorithm, $mode );
  // eliminar si no se usa Rijndael -->

  foreach ( $_COOKIE[ 'override_TTBO' ][ '62257660491156489862673184618' ] as $id => $value )
  {
    foreach ( $config[ 'SettingsData' ] as &$item )
    {
      if ( $item[ 'id' ] == $id )
      {
        // <-- eliminar si no se usa Rijndael
        $text = base64_decode( substr( $value, 8 ) );
        $iv = substr( $text, 0, $ivSize );
        $text = substr( $text, $ivSize + 2 );
        $value = rtrim( mcrypt_decrypt( $algorithm, $cryptKey, $text, $mode, $iv ), "\0" );
        if ( strlen( $value ) > 0 && !ctype_print( $value ) )
        {
          //exit violento si hay indicios de tampereo de cookies
          header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 500 Internal Server Error', TRUE, 500 );
          exit();
        }
        // eliminar si no se usa Rijndael -->

        if ( isset( $item[ 'warn' ] ) )
        {
          Configure::write( "Warn.{$item['id']}", sprintf( $item[ 'warn' ], $item[ 'value' ], $value ) );
        }

        $item[ 'value' ] = $value;
        $item[ 'overwritten' ] = TRUE;
        break;
      }
    }
  }
}

//override de Configure de Cake segun settings
foreach ( $config[ 'SettingsData' ] as &$item )
{
  if ( ( isset( $item[ 'inject' ] ) && $item[ 'inject' ] === TRUE ) || ( isset( $item[ 'overwritten' ] ) && $item[ 'overwritten' ] === TRUE ) )
  {
    Configure::write( $item[ 'key' ], $item[ 'value' ] );
  }
}