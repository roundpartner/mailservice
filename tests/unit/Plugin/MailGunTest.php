<?php

namespace RoundPartner\Test\Unit\Plugin;

class MailGunTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \MailService\Plugin\MailGun
     */
    protected $service;

    /**
     * Set up a test
     */
    public function setUp()
    {
        parent::setUp();
        $config = new \MailService\Entity\Configuration();
        $config->key = 'fakekey';
        $config->endpoint = 'mailinator.com';
        $this->service = new \MailService\Plugin\MailGun($config);
    }

    /**
     * Tests sending mail returns response
     */
    public function testSendMail()
    {
        $this->assertInstanceOf('\MailService\Entity\Response', $this->callSendMessage());
    }

    /**
     * Tests that mail was successfully sent
     */
    public function testSendMailSentSuccessfully()
    {
        $this->assertEquals(200, $this->callSendMessage()->responseCode);
    }

    /**
     * Calls send message function
     *
     * @return \MailService\Entity\Response
     */
    protected function callSendMessage()
    {
        // todo: tidy up this test stub
        $mock = $this->getMockBuilder('Mailgun')
            ->setMethods(array('sendMessage'))
            ->getMock();
        $mock->expects($this->any())
            ->method('sendMessage')
            ->will($this->returnValue((object) array(
                'http_response_body' => (object) array(
                    'id' => '<123.456.789@example.org',
                    'message' => 'Queued. Thank you.',
                ),
                'http_response_code' => 200,
            )));
        $this->service->setService($mock);

        $postData = array(
            'from' => 'Console <test@mailinator.com>',
            'to' => 'Test <test@mailinator.com>',
            'subject' => 'Test message',
            'text' => 'This is a test',
            'o:tracking' => false,
        );
        return $this->service->sendMessage($postData);
    }
}
