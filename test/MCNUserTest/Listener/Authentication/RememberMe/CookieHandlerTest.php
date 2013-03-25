<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNUserTest\Listener\Authentication;

use DateInterval;
use DateTime;
use MCNUser\Authentication\AuthEvent;
use MCNUser\Entity\AuthToken;
use MCNUser\Entity\User;
use MCNUser\Listener\Authentication\RememberMe\CookieHandler;
use Zend\Http\Request;
use Zend\Http\Response;
use MCNUser\Options\Authentication\Plugin\RememberMe as Options;

/**
 * @property User entity
 * @property Request request
 * @property Response response
 * @property \PHPUnit_Framework_MockObject_MockObject tokenService
 * @property \PHPUnit_Framework_MockObject_MockObject plugin
 * @property AuthEvent event
 * @property Options options
 */
class CookieHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->entity = new User();
        $this->entity->setEmail('hello@world.com');

        $this->request  = new Request();
        $this->response = new Response();

        $this->tokenService = $this->getMock('MCNUser\Authentication\TokenService', array(), array(), '', false);
        $this->options      = new Options();


        $this->event = new AuthEvent(AuthEvent::EVENT_AUTH_SUCCESS, $this->entity, array('request' => $this->request));
        $this->listener = new CookieHandler($this->tokenService, $this->response, $this->options);
    }

    public function testNoHeaderIfMissingOrFalseRememberMePostParam()
    {
        $this->listener->setRememberMeCookie($this->event);

        $this->assertEquals(0, count($this->response->getHeaders()));

        $this->request->getPost()->set('remember_me', false);

        $this->listener->setRememberMeCookie($this->event);

        $this->assertEquals(0, count($this->response->getHeaders()));
    }

    public function testHeaderOnRememberMe()
    {
        $this->listener->setRememberMeCookie($this->event);

        $this->request->getPost()->set('remember_me', true);

        $token = new AuthToken();
        $token->fromArray(array(
            'token' => 'hash'
        ));

        $this->tokenService
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($token));

        $this->listener->setRememberMeCookie($this->event);

        /**
         * @var \Zend\Http\Header\SetCookie $cookie
         */
        $cookie = $this->response->getHeaders()->get('SetCookie')[0];

        $this->assertNull($cookie->getExpires());
        $this->assertEquals('hello@world.com|hash', $cookie->getValue());
    }

    public function testHeaderExpires()
    {
        $this->listener->setRememberMeCookie($this->event);

        $this->request->getPost()->set('remember_me', true);

        $dt = new DateTime();
        $dt->add(new DateInterval('PT1H'));

        $token = new AuthToken();
        $token->fromArray(array(
            'token'       => 'hash',
            'valid_until' => $dt
        ));

        $this->tokenService
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($token));

        $this->listener->setRememberMeCookie($this->event);

        /**
         * @var \Zend\Http\Header\SetCookie $cookie
         */
        $cookie = $this->response->getHeaders()->get('SetCookie')[0];

        $this->assertEquals($dt->getTimestamp(), strtotime($cookie->getExpires()));
        $this->assertEquals('hello@world.com|hash', $cookie->getValue());
    }

    public function testRemoveCookie()
    {
        $this->listener->clearCookieOnLogout($this->event);

        /**
         * @var \Zend\Http\Header\SetCookie $cookie
         */
        $cookie = $this->response->getHeaders()->get('SetCookie')[0];

        $this->assertEquals(0,  strtotime($cookie->getExpires()));
        $this->assertEquals('', $cookie->getValue());
    }
}