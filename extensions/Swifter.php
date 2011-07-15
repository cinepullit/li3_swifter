<?php
/**
 * li3_swifter: e-mail library for lithium framework that uses Swiftmailer.
 *
 * @copyright  Copyright 2011, Tobias Sandelius (http://sandelius.org)
 * @license    http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_swifter\extensions;

use Swift_Mailer;
use Swift_Message;
use Swift_MailTransport;
use Swift_SmtpTransport;
use lithium\template\View;
use lithium\core\Libraries;

/**
 * The `Swifter` class allow us to quickly send e-mails using `Swiftmailer`.
 *
 * @link http://swiftmailer.org/
 * @uses lithium\template\View
 * @uses lithium\core\Libraries
 */
class Swifter {

    /**
     * Quick mailer configurations.
     *
     * @see li3_swifter\extensions\Swifter::__init
     * @var array
     */
    protected static $_config = array();

    /**
     * Get global configurations for Swiftmailer.
     *
     * @return void
     */
    public static function __init() {
        static::$_config = Libraries::get('li3_swifter') + array(
            'from' => null,
            'host' => 'smtp.example.org',
            'port' => 25,
            'username' => null,
            'password' => null,
        );
    }

    /**
     * Send mail using `smtp` transport.
     *
     * @param array $options Message and smtp options.
     * @return boolean
     */
    public static function smtp(array $options) {
        $options += array(
            'host' => static::$_config['host'],
            'port' => static::$_config['port'],
            'username' => static::$_config['username'],
            'password' => static::$_config['password'],
        );

        $transport = Swift_SmtpTransport::newInstance($options['host'], $options['port'])
                   ->setUsername($options['username'])
                   ->setPassword($options['password']);

        $mailer = Swift_Mailer::newInstance($transport);
        return $mailer->send(static::_message($options));
    }

    /**
     * Send email using `mail` transport.
     *
     * @param array $options Message options.
     * @return boolean
     */
    public static function mail(array $options) {
        $transport = Swift_MailTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        return $mailer->send(static::_message($options));
    }

    /**
     * Build the e-mail message we should send.
     *
     * @param array $options Message options.
     * @return object `Swift_Message`
     */
    protected static function _message(array $options) {
        $options += array(
            'from' => static::$_config['from'],
            'to' => array('foo@bar.tld' => 'Foo Bar'),
            'cc' => false,
            'bcc' => false,
            'subject' => '',
            'body' => '',
            'template' => false,
            'data' => array(), // Data to be available in the view
        );

        $message = Swift_Message::newInstance($options['subject'])
                 ->setTo($options['to'])
                 ->setFrom($options['from']);

        if ($options['cc']) {
            $message->addCc($options['cc']);
        }
        if ($options['bcc']) {
            $message->addCc($options['bcc']);
        }

        if ($options['template']) {
            $view  = new View(array(
                'loader' => 'File',
                'renderer' => 'File',
                'paths' => array(
                    'template' => '{:library}/views/emails/{:template}.mail.php'
                )
            ));

            $message->setBody($view->render('template', $options['data'], array(
                'template' => $options['template'],
                'layout' => false,
            )), 'text/html');
        }
        else {
            $message->setBody($options['body']);
        }

        return $message;
    }
}

?>