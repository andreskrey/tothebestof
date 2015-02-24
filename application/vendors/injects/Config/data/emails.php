<?php
/**
 * Created by IntelliJ IDEA.
 * User: ramiro.araujo
 * Date: 6/5/13
 * Time: 4:13 PM
 * To change this template use File | Settings | File Templates.
 */

$config = array(
  'EmailsData' => array(
    array(
      'id'          => '1',
      'uuid'        => '51aeb2b9-12b4-4006-ad76-0e989bd20d94', //unique uuid, http://www.uuidgenerator.net/
      'name'        => 'Demo HTML',
      'description' => 'Descripción para la Demo HTML',

      'key'         => 'html-key', //unique key

      'format'      => 'html', //or html, or both
      'from_name'   => 'Nombre Remitente',
      'from_email'  => 'name@domain.com',
      'subject'     => 'Asunto para el Email HTML',
      'config'      => 'default',
      'template'    => array( 'default', 'blank' ), //view first, layout later
      'vars'        => array( 'texto', 'otroTexto' ), //optional, helps previewing the email from the application
    ),
    array(
      'id'          => '2',
      'uuid'        => '41aeb2b9-12b4-4006-ad76-0e989bd20d94',
      'name'        => 'Demo Texto Plano',
      'description' => 'Descripción para la Demo Texto Plano',

      'key'         => 'text-key',

      'format'      => 'text',
      'from_name'   => 'Nombre Remitente',
      'from_email'  => 'name@domain.com',
      'subject'     => 'Asunto para el Email Texto Plano',
      'config'      => 'default',
      'template'    => array( 'default', 'blank' ),
      'vars'        => array( 'texto', 'otroTexto' ),
    )
  )
);