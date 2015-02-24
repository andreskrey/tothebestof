<?php
/**
 * Configuracion de entorno.
 */

if (isset($_SERVER['HTTP_HOST'])) {
    switch ($_SERVER['HTTP_HOST']) {

        case 'test.tothebestof.com':
            $config = array(
                'Config' => array(
                    'environment' => 'staging'
                ),
                'debug' => 2,
                'Cache.disable' => TRUE,

                'DebugKit' => array(
                    'disable' => TRUE
                ),
                'AssetCompress' => array(
                    'raw' => TRUE
                )
            );
            break;

        case 'tothebestof.com':
        case 'www.tothebestof.com':
            $config = array(
                'Config' => array(
                    'environment' => 'production'
                ),
                'debug' => 0,
                'Cache' => array(
                    'disable' => FALSE
                ),
                'DebugKit' => array(
                    'disable' => TRUE
                ),
                'AssetCompress' => array(
                    'raw' => FALSE
                ),
                'Session' => array(
                    'timeout' => 600,
                )
            );

            break;

        case 'localhost':
            $config = array(
                'Config' => array(
                    'environment' => 'local'
                ),
                'AssetCompress' => array(
                    'raw' => TRUE
                ),
                'DebugKit' => array(
                    'disable' => TRUE
                ),
                'Cache.disable' => TRUE,
                'Session' => array(
                    'timeout' => 600,
                )

            );

            break;

        default:

            $config = array(
                'Config' => array(
                    'environment' => 'production'
                ),
                'debug' => 0,
                'Cache' => array(
                    'disable' => FALSE
                ),
                'DebugKit' => array(
                    'disable' => TRUE
                ),
                'AssetCompress' => array(
                    'raw' => FALSE
                ),
                'Session' => array(
                    'timeout' => 600,
                )
            );

            break;
    }
} //el default es local
else {
    $config = array(
        'Config' => array(
            'environment' => 'local'
        ),
        'AssetCompress' => array(
            'raw' => TRUE
        )

    );
}

/**
 * Configuraciones extra que se mantienen identicas en todos los entornos, pero por semántica y comodidad es mejor
 * ponerlas acá
 */
//$config['Config']['SomethingElse'] = 'Nothing really';