<?php

/**
 * Created by IntelliJ IDEA.
 * User: ramiro
 * Date: 6/4/13
 * Time: 5:39 PM
 * To change this template use File | Settings | File Templates.
 */

App::uses('CakeEmail', 'Network/Email');
/**
 * Class ZenEmail
 */
class ZenEmail extends CakeEmail
{
    /**
     *
     */
    const EMAIL_CLIENT = 'Zen Email';

    /**
     * @var string
     */
    protected static $config = 'default';

    /**
     * @var
     */
    public static $lastError;


    /**
     * @param string $key
     * @param string $toEmail
     * @param string $toName
     * @param array  $viewVars
     * @param array  $attachments
     * @param null   $subject
     * @param string $config
     * @param null   $fromEmail
     * @param null   $fromName
     *
     * @internal param string $debug
     * @return array|bool
     */
    public static function deliver($key, $toEmail, $toName = NULL, $viewVars = array(), $attachments = null, $subject = null, $fromEmail = null, $fromName = null, $config = null)
    {
        $emailData = self::getEmail($key);

        $attachments = $attachments ? $attachments : array();
        $subject = $subject ? $subject : $emailData['subject'];
        $fromEmail = $fromEmail ? $fromEmail : $emailData['from_email'];
        $fromName = $fromName ? $fromName : $emailData['from_name'];
        $config = $config ? $config : $emailData['config'];


        $email = new ZenEmail();
        $email
            ->config($config)
            ->template($emailData['template'][0], $emailData['template'][1])
            ->viewVars($viewVars)
            ->emailFormat($emailData['format'])
            ->to($toEmail, $toName)
            ->from(array($fromEmail => $fromName))
            ->attachments($attachments)
            ->subject($subject);

        try {
            $email->send();

            self::$lastError = FALSE;

            return TRUE;
        } catch (SocketException $e) {
            //@todo devolver la excepcion y manejarla? o la complica para el uso q le vamos a dar? al menos loguear
            self::$lastError = $e->getMessage();

            return FALSE;
        }
    }


    /**
     * @param       $key
     * @param array $viewVars
     * @param       $format
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public static function render($key, $viewVars = array(), $format)
    {
        $emailData = self::getEmail($key);

        if ($emailData['format'] != $format && $emailData['format'] != 'both') {
            throw new InvalidArgumentException('El Email no tiene el formato especificado');
        }

        $zenEmail = new ZenEmail();
        $zenEmail
            ->emailFormat($format)
            ->template($emailData['template'][0], $emailData['template'][1])
            ->viewVars($viewVars);

        $response = $zenEmail->renderTemplate();

        return $response;
    }


    /**
     * @param $key
     *
     * @return array
     * @throws NotFoundException
     */
    protected static function getEmail($key)
    {
        /** @var Email $emailModel */
        $emailModel = ClassRegistry::init('Email');
        $emailData = $emailModel->find('first', array('conditions' => array('Email.key' => $key)));

        if (!$emailData) {
            throw new NotFoundException('Email no encontrado');
        }

        return $emailData['Email'];
    }

    public static function getConfigs()
    {
        config('email');
        $configs = array_keys(get_object_vars(new EmailConfig()));
        return array_combine($configs, $configs);
    }

    /**
     * @return string
     */
    private function renderTemplate()
    {
        return join("\n", $this->_render($this->_wrap(NULL)));
    }
}