<?php
/**
 * +-----------------------------------------------------------------------+
 * | Copyright (c) 2010, David Coallier & echolibre ltd                    |
 * | All rights reserved.                                                  |
 * |                                                                       |
 * | Redistribution and use in source and binary forms, with or without    |
 * | modification, are permitted provided that the following conditions    |
 * | are met:                                                              |
 * |                                                                       |
 * | o Redistributions of source code must retain the above copyright      |
 * |   notice, this list of conditions and the following disclaimer.       |
 * | o Redistributions in binary form must reproduce the above copyright   |
 * |   notice, this list of conditions and the following disclaimer in the |
 * |   documentation and/or other materials provided with the distribution.|
 * | o The names of the authors may not be used to endorse or promote      |
 * |   products derived from this software without specific prior written  |
 * |   permission.                                                         |
 * |                                                                       |
 * | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
 * | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
 * | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
 * | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
 * | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
 * | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
 * | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
 * | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
 * | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
 * | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
 * | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
 * |                                                                       |
 * +-----------------------------------------------------------------------+
 * | Author: David Coallier <david@echolibre.com>                          |
 * +-----------------------------------------------------------------------+
 *
 * PHP version 5
 *
 * @category  Services
 * @package   Services_Capsule
 * @author    David Coallier <david@echolibre.com>
 * @copyright echolibre ltd. 2009-2010
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @link      http://github.com/davidcoallier/Services_Capsule
 * @version   GIT: $Id$
 */

/**
 * Services_Capsule
 *
 * @category Services
 * @package  Services_Capsule
 * @author   David Coallier <david@echolibre.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @link     http://github.com/davidcoallier/Services_Capsule
 * @version  Release: @package_version@
 */
abstract class Services_Capsule_Common
{
    /**
     * Sub section. History of an Opportunity for example
     * is Services_Capsule_Opportunity_History
     *
     * @var string The sub section mame
     */
    protected $subSections = array();
    
    /**
     * The name of the module to call
     *
     * @var string The module name to call
     */
    protected $moduleName;
    
    /**
     * The identification token
     * 
     * @link https://sample.capsulecrm.com/user/api
     * @var string The web service identification token
     */
    protected $token;
    
    /**
     * The application name
     *
     * @var string The actual app name of your company
     */
    protected $appName;
    
    /**
     * An object of the HTTP_Request2 Client
     *
     * @var HTTP_Request2 An HTTP_Request2 instance.
     */
    protected $client;
    
    /**
     * The capsule webservice endpoint in a sprintf configurable url. 
     *
     * @see $this->appName 
     * @var string The web service endpoint.
     */
    protected $endpoint = 'https://%s.capsulecrm.com/api/%s';
    
    /**
     * Magical Getter
     *
     * @throws Services_Capsule_RuntimeException
     *
     * @param string $sub Items, Meetings, Notes, Projects or User.
     *
     * @return mixed Services_Capsule_*
     */
    public function __get($section)
    {
        $section = ucwords(strtolower($section));
        
        switch ($section) {
            case 'History':
            case 'Tag':
            case 'Party':
            case 'Opportunity':
            case 'Customfield':
            case 'Milestone':
			case 'Cases':
			case 'Task':
            
            $currentModule = ucfirst(strtolower($this->moduleName));

            if (!isset($this->subSections[$section])) {
                $classname = 'Services_Capsule_'. $currentModule . '_' .$section;

                if (!class_exists($classname)) {
                    $filename  = str_replace('_', '/', $classname) . '.php';
                    
                    if (!(include $filename)) {
                        throw new Services_Capsule_RuntimeException(
                            'File ' . $filename . ' does not exist.'
                        );
                    }
                    
                }

                $this->subSections[$section] = new $classname;
            }
            
            $this->subSections[$section]
                ->setToken($this->token)
                ->setAppName($this->appName)
                ->setModuleName(strtolower($currentModule));

            return $this->subSections[$section];
            break;

        default:
            throw new Services_Capsule_RuntimeException(
                'Section '. $section .' is not a valid API call. If you believe this ' . 
                'is wrong please report a bug on http://pear.php.net/Services_Capsule'
            );
        }
    }
    
    /**
     * Set the identification token
     * 
     * This method is used to set the identification token
     * that you can gather from the capsule website.
     *
     * @link https://sampl.capsulecrm.com/user/api
     *
     * @param  string $token The web service token.
     * @return object $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    /**
     * Set the application name
     *
     * This method is used to set the first part of the
     * $this->endpoint variable (%s). 
     *
     * @param  string $appName  The name of your application (company name).
     * @return object $this
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
        return $this;
    }
    
    /**
     * Set the module naem
     * 
     * This method is used to set the module name that the
     * child will be invoking.
     *
     * @param  string $moduleName The module name to use.
     * @return object $this
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }
    
    /**
     * Send the request to the capsule web service
     *
     * This method is used to send all the requests to the capsule web service.
     *
     * @throws Services_Capsule_UnexpectedValueException
     * @throws Services_Capsule_RuntimeException
     *
     * @uses   HTTP_Request2
     * 
     * @param  string $url     The URL to request.
     * @param  string $method  The HTTP Method (Use HTTP_Request2::METHOD_*)
     *                         Default is HTTP_Request2::METHOD_GET (GET).
     * @param  string $data    The data to pass to the body. Null by default.
     */
    protected function sendRequest($url, $method = HTTP_Request2::METHOD_GET, $data = null)
    {
        if (!isset($this->appName)) {
            throw new Services_Capsule_UnexpectedValueException(
                'Please set an app name.'
            );
        }
        
        if (!isset($this->client) || !($this->client instanceof HTTP_Request2)) {
            $this->client = new HTTP_Request2();
            $this->client->setAdapter('curl');
            // Modify the security parameters for this connection because for some odd reason
            // it did not function in the previous case.
            $z = $this->client->getConfig();
            $z['ssl_verify_peer'] = false;
            $z['ssl_verify_host'] = false;
            $this->client->setConfig($z);
        }
        
        $finalUrl = sprintf($this->endpoint, $this->appName, $this->moduleName);
        $finalUrl = $finalUrl . $url;

        $this->client
             ->setHeader('Content-Type: application/json')
             ->setHeader('Accept: application/json')
             ->setAuth($this->token, 'x')
             ->setMethod($method)
             ->setUrl($finalUrl);
             
        if (!is_null($data)) {
            $this->client->setBody($data);
        }
        
        try {
            $response = $this->client->send();
        } catch (HTTP_Request2_Exception $e) {
            throw new Services_Capsule_RuntimeException($e);
        }
        
        return $response;
    }
    
    /**
     * Parse the response
     *
     * This method is used to parse the response that is returned from
     * the request that was made in $this->sendRequest().
     *
     * @throws Services_Capsule_RuntimeException
     *
     * @param  HTTP_Request2_Response $response  The response from the webservice.
     * @return mixed               stdClass|bool A stdClass object of the 
     *                                           json-decode'ed body or true if
     *                                           the code is 201 (created)
     */
    protected function parseResponse(HTTP_Request2_Response $response)
    {
        $body = $response->getBody();
        $return = json_decode($body);
        
        if (!($return instanceof stdClass)) {
            if ($response->getStatus() == 201 || $response->getStatus() == 200) {
                return true;
            }
            
            throw new Services_Capsule_RuntimeException(
                'Invalid response with no valid json body'
            );
        }
        
        return $return;
    }
}