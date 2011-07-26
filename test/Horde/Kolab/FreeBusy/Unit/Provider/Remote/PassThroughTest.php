<?php
/**
 * Test the remote pass-through provider.
 *
 * PHP version 5
 *
 * @category   Kolab
 * @package    Kolab_FreeBusy
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link       http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */

/**
 * Prepare the test setup.
 */
require_once dirname(__FILE__) . '/../../../Autoload.php';

/**
 * Test the remote pass-through provider.
 *
 * Copyright 2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @category   Kolab
 * @package    Kolab_FreeBusy
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link       http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */
class Horde_Kolab_FreeBusy_Unit_Provider_Remote_PassThroughTest
extends Horde_Kolab_FreeBusy_TestCase
{
    public function testPassThrough()
    {
        $headers = $this->_trigger()->getHeaders();
        $this->assertTrue(isset($headers['X-Redirect-To']));
    }

    public function testPassThroughTriggerUrl()
    {
        $headers = $this->_trigger()->getHeaders();
        $this->assertEquals(
            'https://mail%40example.org:TEST@example.com/freebusy/trigger/remote%40example.org/test.pfb',
            $headers['X-Redirect-To']
        );
    }

    public function testPassThroughFetchUrl()
    {
        $headers = $this->_fetch()->getHeaders();
        $this->assertEquals(
            'https://mail%40example.org:TEST@example.com/freebusy/remote%40example.org.ifb',
            $headers['X-Redirect-To']
        );
    }

    public function testExtendedPassThroughTriggerUrl()
    {
        $headers = $this->_trigger(
            array('extended' => true)
        )->getHeaders();
        $this->assertEquals(
            'https://mail%40example.org:TEST@example.com/freebusy/trigger/remote%40example.org/test.pxfb',
            $headers['X-Redirect-To']
        );
    }

    public function testExtendedPassThroughFetchUrl()
    {
        $headers = $this->_fetch(
            array('extended' => true)
        )->getHeaders();
        $this->assertEquals(
            'https://mail%40example.org:TEST@example.com/freebusy/remote%40example.org.xfb',
            $headers['X-Redirect-To']
        );
    }

    private function _trigger($params = array())
    {
        $response = new Horde_Controller_Response();
        if (!empty($params['extended'])) {
            $path = 'trigger/remote%40example.org/test.pxfb';
        } else {
            $path = 'trigger/remote%40example.org/test.pfb';
        }
        $this->_provider($path)->trigger($response, $params);
        return $response;
    }

    private function _fetch($params = array())
    {
        $response = new Horde_Controller_Response();
        if (!empty($params['extended'])) {
            $path = 'remote%40example.org.xfb';
        } else {
            $path = 'remote%40example.org.ifb';
        }
        $this->_provider($path)->fetch($response, $params);
        return $response;
    }

    private function _provider($path, $response = 200)
    {
        $request = new Horde_Controller_Request_Mock();
        $request->setPath($path);
        return new Horde_Kolab_FreeBusy_Provider_Remote_PassThrough(
            $this->getRemoteOwner(),
            $request,
            $this->_getUser(),
            $this->_getClient($response)
        );
    }

    private function _getUser()
    {
        return $this->getDb()->getUser('mail@example.org', 'TEST');
    }

    private function _getClient($code = 200)
    {
        $string = 'RESPONSE';
        $body = new Horde_Support_StringStream($string);
        $response = new Horde_Http_Response_Mock('', $body->fopen());
        $response->code = $code;
        $request = new Horde_Http_Request_Mock();
        $request->setResponse($response);
        return new Horde_Http_Client(array('request' => $request));
    }

}