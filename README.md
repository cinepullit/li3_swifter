# li3_swifter
Lithium library for sending emails uising [Swiftmailer](http://swiftmailer.org/). It contains
a class that allow us to send e-mails using template files, read `usage` for more information.

Swiftmailer version: `4.1.1`

## Usage:
To enable the library add the following line at the end of `app/config/bootstrap/libraries.php`:

    Libraries::add('li3_swifter');

From here on you can access all Swiftmailer classes and create your emails by yourself. Read the
Swiftmailer [docs](http://swiftmailer.org/docs/introduction.htm/) or you can use the
`li3_swifter\extensions\Swifter` class.

We can add global configurations to the `Swifter` class when adding the library. It's useful
when setting `smtp` configurations that's used in several locations.

    Libraris::add('li3_swifter', array(
        'host' => '',
        'port' => 25,
        'username' => 'your_username',
        'password' => 'your_password',
    ));

### Mail transport

    Swifter::mail(array $options);
    Swifter::smtp(array $options);

The `$options` supports the following items:

    $options = array(
        'from' => static::$_config['from'],
        'to' => array('foo@bar.tld' => 'Foo Bar'),
        'cc' => false,
        'bcc' => false,
        'subject' => '',

        // Content body if not using template
        'body' => '',

        // Template to use
        'template' => false,

        // Data to be available in the template
        'data' => array(),
    );

If we've not set `smtp` configurations when adding the library, or need to use other configurations, we
can add them to the `$options` array in `Swifter::smtp`.

    $options = array(
        'host' => 'smtp.example.org',
        'port' => 25,
        'username' => null,
        'password' => null,
    );