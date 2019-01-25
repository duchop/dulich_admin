<?php

class Swift_Signers_SMimeSignerTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var Swift_StreamFilters_StringReplacementFilterFactory
     */
    protected $replacementFactory;

    protected $samplesDir;

    protected function setUp()
    {
        $this->replacementFactory = Swift_DependencyContainer::getInstance()->lookup('transport.replacementfactory');
        
        $this->samplesDir = str_replace('\\', '/', realpath(__DIR__ . '/../../../_samples/')) . '/';
    }

    public function testUnSignedMessage()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $this->assertEquals('Here is the message itself', $message->getBody());
    }

    public function testSignedMessage()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key');
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Here is the message itself
--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        $this->assertValidVerify($expectedBody, $messageStream);
        unset($messageStream);
    }

    public function testSignedMessageWithFullyWrappedMessage()
    {
        $message = (new Swift_Message('Middle-out compression secrets'))->setFrom([
            'richard@piedpiper.com' => 'Richard Hendricks'
        ])
            ->setTo([
            'jared@piedpiper.com' => 'Jared Dunn'
        ])
            ->setBody('Here goes the entire algorithm...');
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key');
        
        // Tell the signer to wrap the full MIME message
        $signer->setWrapFullMessage(true);
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
Content-Type: message/rfc822; charset=utf-8
Content-Transfer-Encoding: 7bit

Message-ID: <[a-f0-9]+@swift.generated>
Date: .*
Subject: Middle-out compression secrets
From: Richard Hendricks <richard@piedpiper.com>
To: Jared Dunn <jared@piedpiper.com>
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Here goes the entire algorithm...
--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        $this->assertValidVerify($expectedBody, $messageStream);
        unset($messageStream);
    }

    public function testSignedMessageExtraCerts()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign2.crt', $this->samplesDir . 'smime/sign2.key', PKCS7_DETACHED, $this->samplesDir . 'smime/intermediate.crt');
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Here is the message itself
--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        $this->assertValidVerify($expectedBody, $messageStream);
        unset($messageStream);
    }

    public function testSignedMessageBinary()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key', PKCS7_BINARY);
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! preg_match('#^application/(x\-)?pkcs7-mime; smime-type=signed\-data;#', $headers['content-type'])) {
            $this->fail('Content-type does not match.');
            
            return false;
        }
        
        $this->assertEquals($headers['content-transfer-encoding'], 'base64');
        $this->assertEquals($headers['content-disposition'], 'attachment; filename="smime.p7m"');
        
        $expectedBody = '(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})';
        
        $messageStreamClean = $this->newFilteredStream();
        
        $this->assertValidVerify($expectedBody, $messageStream);
        unset($messageStreamClean, $messageStream);
    }

    public function testSignedMessageWithAttachments()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $message->attach(Swift_Attachment::fromPath($this->samplesDir . '/files/textfile.zip'));
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key');
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
Content-Type: multipart/mixed;
 boundary="([a-z0-9\\'\\(\\)\\+_\\-,\\.\\/:=\\?\\ ]{0,69}[a-z0-9\\'\\(\\)\\+_\\-,\\.\\/:=\\?])"


--\\1
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Here is the message itself

--\\1
Content-Type: application/zip; name=textfile\\.zip
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=textfile\\.zip

UEsDBAoAAgAAAMi6VjiOTiKwLgAAAC4AAAAMABUAdGV4dGZpbGUudHh0VVQJAAN3vr5Hd76\\+R1V4
BAD1AfUBVGhpcyBpcyBwYXJ0IG9mIGEgU3dpZnQgTWFpbGVyIHY0IHNtb2tlIHRlc3QuClBLAQIX
AwoAAgAAAMi6VjiOTiKwLgAAAC4AAAAMAA0AAAAAAAEAAACkgQAAAAB0ZXh0ZmlsZS50eHRVVAUA
A3e\\+vkdVeAAAUEsFBgAAAAABAAEARwAAAG0AAAAAAA==

--\\1--

--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        
        $this->assertValidVerify($expectedBody, $messageStream);
        unset($messageStream);
    }

    public function testEncryptedMessage()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $originalMessage = $this->cleanMessage($message->toString());
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setEncryptCertificate($this->samplesDir . 'smime/encrypt.crt');
        $message->attachSigner($signer);
        
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! preg_match('#^application/(x\-)?pkcs7-mime; smime-type=enveloped\-data;#', $headers['content-type'])) {
            $this->fail('Content-type does not match.');
            
            return false;
        }
        
        $expectedBody = '(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})';
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStream->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $this->assertEquals($originalMessage, $decryptedMessageStream->getContent());
        unset($decryptedMessageStream, $messageStream);
    }

    public function testEncryptedMessageWithFullyWrappedMessage()
    {
        $message = (new Swift_Message('Middle-out compression secrets'))->setFrom([
            'richard@piedpiper.com' => 'Richard Hendricks'
        ])
            ->setTo([
            'jared@piedpiper.com' => 'Jared Dunn'
        ])
            ->setBody('Here goes the entire algorithm...');
        
        $originalMessage = $message->toString();
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setEncryptCertificate($this->samplesDir . 'smime/encrypt.crt');
        $signer->setWrapFullMessage(true);
        $message->attachSigner($signer);
        
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! preg_match('#^application/(x\-)?pkcs7-mime; smime-type=enveloped\-data;#', $headers['content-type'])) {
            $this->fail('Content-type does not match.');
            
            return false;
        }
        
        $expectedBody = '(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})';
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStream->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $decryptedMessage = $decryptedMessageStream->getContent();
        $decryptedHeaders = self::getHeadersOfMessage($decryptedMessage);
        $this->assertEquals('message/rfc822; charset=utf-8', $decryptedHeaders['content-type']);
        $this->assertEquals('7bit', $decryptedHeaders['content-transfer-encoding']);
        
        $decryptedMessageBody = self::getBodyOfMessage($decryptedMessage);
        $this->assertEquals($originalMessage, $decryptedMessageBody);
        unset($decryptedMessageStream, $messageStream);
    }

    public function testEncryptedMessageWithMultipleCerts()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $originalMessage = $this->cleanMessage($message->toString());
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setEncryptCertificate([
            $this->samplesDir . 'smime/encrypt.crt',
            $this->samplesDir . 'smime/encrypt2.crt'
        ]);
        $message->attachSigner($signer);
        
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! preg_match('#^application/(x\-)?pkcs7-mime; smime-type=enveloped\-data;#', $headers['content-type'])) {
            $this->fail('Content-type does not match.');
            
            return false;
        }
        
        $expectedBody = '(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})';
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStream->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $this->assertEquals($originalMessage, $decryptedMessageStream->getContent());
        unset($decryptedMessageStream);
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStream->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt2.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt2.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $this->assertEquals($originalMessage, $decryptedMessageStream->getContent());
        unset($decryptedMessageStream, $messageStream);
    }

    public function testSignThenEncryptedMessage()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key');
        $signer->setEncryptCertificate($this->samplesDir . 'smime/encrypt.crt');
        $message->attachSigner($signer);
        
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! preg_match('#^application/(x\-)?pkcs7-mime; smime-type=enveloped\-data;#', $headers['content-type'])) {
            $this->fail('Content-type does not match.');
            
            return false;
        }
        
        $expectedBody = '(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})';
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStream->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $entityString = $decryptedMessageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Here is the message itself
--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        
        if (! $this->assertValidVerify($expectedBody, $decryptedMessageStream)) {
            return false;
        }
        
        unset($decryptedMessageStream, $messageStream);
    }

    public function testEncryptThenSignMessage()
    {
        $message = (new Swift_Message('Wonderful Subject'))->setFrom([
            'john@doe.com' => 'John Doe'
        ])
            ->setTo([
            'receiver@domain.org',
            'other@domain.org' => 'A name'
        ])
            ->setBody('Here is the message itself');
        
        $originalMessage = $message->toString();
        
        $signer = new Swift_Signers_SMimeSigner();
        $signer->setSignCertificate($this->samplesDir . 'smime/sign.crt', $this->samplesDir . 'smime/sign.key');
        $signer->setEncryptCertificate($this->samplesDir . 'smime/encrypt.crt');
        $signer->setSignThenEncrypt(false);
        $message->attachSigner($signer);
        
        $messageStream = $this->newFilteredStream();
        $message->toByteStream($messageStream);
        $messageStream->commit();
        
        $entityString = $messageStream->getContent();
        $headers = self::getHeadersOfMessage($entityString);
        
        if (! ($boundary = $this->getBoundary($headers['content-type']))) {
            return false;
        }
        
        $expectedBody = <<<OEL
This is an S/MIME signed message

--$boundary
(?P<encrypted_message>Content-Type: application/(x\-)?pkcs7-mime; smime-type=enveloped-data;
 name="smime\.p7m"; charset=utf-8
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7m"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})


)--$boundary
Content-Type: application/(x\-)?pkcs7-signature; name="smime\.p7s"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="smime\.p7s"

(?:^[a-zA-Z0-9\/\\r\\n+]*={0,2})

--$boundary--
OEL;
        
        if (! $this->assertValidVerify($expectedBody, $messageStream)) {
            return false;
        }
        
        $expectedBody = str_replace("\n", "\r\n", $expectedBody);
        if (! preg_match('%' . $expectedBody . '*%m', $entityString, $entities)) {
            $this->fail('Failed regex match.');
            
            return false;
        }
        
        $messageStreamClean = new Swift_ByteStream_TemporaryFileByteStream();
        $messageStreamClean->write($entities['encrypted_message']);
        
        $decryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();
        
        if (! openssl_pkcs7_decrypt($messageStreamClean->getPath(), $decryptedMessageStream->getPath(), 'file://' . $this->samplesDir . 'smime/encrypt.crt', [
            'file://' . $this->samplesDir . 'smime/encrypt.key',
            'swift'
        ])) {
            $this->fail(sprintf('Decrypt of the message failed. Internal error "%s".', openssl_error_string()));
        }
        
        $this->assertEquals($originalMessage, $decryptedMessageStream->getContent());
        unset($messageStreamClean, $messageStream, $decryptedMessageStream);
    }

    protected function assertValidVerify($expected, Swift_ByteStream_TemporaryFileByteStream $messageStream)
    {
        $actual = $messageStream->getContent();
        
        // File is UNIX encoded so convert them to correct line ending
        $expected = str_replace("\n", "\r\n", $expected);
        
        $actual = self::getBodyOfMessage($actual);
        if (! $this->assertRegExp('%^' . $expected . '$\s*%m', $actual)) {
            return false;
        }
        
        $opensslOutput = new Swift_ByteStream_TemporaryFileByteStream();
        $verify = openssl_pkcs7_verify($messageStream->getPath(), null, $opensslOutput->getPath(), [
            $this->samplesDir . 'smime/ca.crt'
        ]);
        
        if (false === $verify) {
            $this->fail('Verification of the message failed.');
            
            return false;
        } elseif (- 1 === $verify) {
            $this->fail(sprintf('Verification of the message failed. Internal error "%s".', openssl_error_string()));
            
            return false;
        }
        
        return true;
    }

    protected function getBoundary($contentType)
    {
        if (! preg_match('/boundary=("[^"]+"|(?:[^\s]+|$))/is', $contentType, $contentTypeData)) {
            $this->fail('Failed to find Boundary parameter');
            
            return false;
        }
        
        return trim($contentTypeData[1], '"');
    }

    protected function newFilteredStream()
    {
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $messageStream->addFilter($this->replacementFactory->createFilter("\r\n", "\n"), 'CRLF to LF');
        $messageStream->addFilter($this->replacementFactory->createFilter("\n", "\r\n"), 'LF to CRLF');
        
        return $messageStream;
    }

    protected static function getBodyOfMessage($message)
    {
        return trim(substr($message, strpos($message, "\r\n\r\n")));
    }

    /**
     * Strips of the sender headers and Mime-Version.
     */
    protected function cleanMessage($content)
    {
        $newContent = '';
        
        $headers = self::getHeadersOfMessage($content);
        foreach ($headers as $headerName => $value) {
            if (! in_array($headerName, [
                'content-type',
                'content-transfer-encoding',
                'content-disposition'
            ])) {
                continue;
            }
            
            $headerName = explode('-', $headerName);
            $headerName = array_map('ucfirst', $headerName);
            $headerName = implode('-', $headerName);
            
            if (strlen($value) > 62) {
                $value = wordwrap($value, 62, "\n ");
            }
            
            $newContent .= "$headerName: $value\r\n";
        }
        
        return $newContent . "\r\n" . self::getBodyOfMessage($content);
    }

    /**
     * Returns the headers of the message.
     *
     * Header-names are lowercase.
     *
     * @param string $message            
     *
     * @return array
     */
    protected static function getHeadersOfMessage($message)
    {
        $headersPosEnd = strpos($message, "\r\n\r\n");
        $headerData = trim(substr($message, 0, $headersPosEnd));
        $headerLines = explode("\r\n", $headerData);
        $headers = [];
        
        if (false === $headerLines) {
            return $headers;
        }
        
        // Transform header lines into an associative array
        $currentHeaderName = '';
        foreach ($headerLines as $headerLine) {
            // Handle headers that span multiple lines
            if (false === strpos($headerLine, ':')) {
                $headers[$currentHeaderName] .= ' ' . trim($headerLine);
                continue;
            }
            
            $header = explode(':', $headerLine, 2);
            $currentHeaderName = strtolower($header[0]);
            $headers[$currentHeaderName] = trim($header[1]);
        }
        
        return $headers;
    }
}
