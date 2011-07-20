# li3_swifter
Lithium library for sending emails uising [Swiftmailer](http://swiftmailer.org/). It contains
a class that allow us to send e-mails using template files, read `usage` for more information.

Swiftmailer version: `4.1.1`

## Usage:
To enable the library add the following line at the end of `app/config/bootstrap/libraries.php`:

    Libraries::add('li3_swifter');

From here on you can access all Swiftmailer classes and create your e-mails by yourself, read the
Swiftmailer [docs](http://swiftmailer.org/docs/introduction.htm/), or you can use the
`li3_swifter\extensions\Swifter` class.

We can add global configurations to the `Swifter` class when adding the library. It's useful
when setting `smtp` configurations that's used in several locations.

    Libraries::add('li3_swifter', array(
        'from' => array('my@mail.tld' => 'My Name'),
        'to' => 'foo@bar.tld', // Useful if all e-mails is for you (contact form)
        'host' => '',
        'port' => 25,
        'username' => 'your_username',
        'password' => 'your_password',
    ));

### E-mail transports

    $boolean = Swifter::mail(array $options);
    $boolean = Swifter::smtp(array $options);

### Configurations
The `$options` array supports the following items:

    $options = array(
        'from' => array('my@mail.tld' => 'My Name'),
        'to' => array('foo@bar.tld', 'bar@foo.tld'),
        'cc' => false,
        'bcc' => false,
        'subject' => '',

        // Content body if not using template
        'body' => '',

        // Template to use
        'template' => false,

        // Data to be available in the template ($subject is added automatically)
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

### Examples
Quickly send an e-mail without a template:

    use li3_swifter\extensions\Swifter;

    Swifter::smtp(array(
        'from' => array('my@email.tld' => 'My Name'),
        'to' => 'foo@bar',
        'body' => 'This is the content body'
    ));

Send a quick e-mail using a `mail` template:

    use li3_swifter\extensions\Swifter;

    $to = 'foo@bar';

    Swifter::smtp(array(
        'from' => array('my@email.tld' => 'My Name'),
        'to' => $to,
        'template' => 'emails/welcome',
        'data' => array('to' => $to),
    ));

    // 'views/emails/welcome.mail.php'
    <html>
    <head>
    <title>Welcome</title>
    </head>
    <body>
        <h1>E-mail sent to: <?=$to ?></h1>
    </body>
    </html>