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
            'id' => '1',
            'uuid' => '51aeb2b9-12b4-4006-ad76-0e989bd20d94', //unique uuid, http://www.uuidgenerator.net/
            'name' => 'Demo HTML',
            'description' => 'Descripción para la Demo HTML',

            'key' => 'default', //unique key

            'format' => 'html', //or html, or both
            'from_name' => 'Tothebestof.com',
            'from_email' => 'password@tothebestof.com',
            'subject' => 'Asunto para el Email HTML',
            'config' => 'default',
            'template' => array('default', 'blank'), //view first, layout later
            'vars' => array(), //optional, helps previewing the email from the application
        ),
        array(
            'id' => '2',
            'uuid' => '1c941e90-5892-4e13-afe8-04ea1c9e831f', //unique uuid, http://www.uuidgenerator.net/
            'name' => 'Contact',
            'description' => 'Descripción para la Demo HTML',

            'key' => 'contact', //unique key

            'format' => 'html', //or html, or both
            'from_name' => 'Tothebestof.com',
            'from_email' => 'andy@tothebestof.com',
            'subject' => 'Contacto',
            'config' => 'default',
            'template' => array('contact', 'blank'), //view first, layout later
            'vars' => array(), //optional, helps previewing the email from the application
        ),
    )
);