<?php
/**
 * +-----------------------------------------------------------------------+
 * | Copyright (c) 2010, David Coallier                                    |
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

require_once 'HTTP/Request2.php';

require_once 'Services/Capsule/Exception.php';
require_once 'Services/Capsule/Common.php';

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
class Services_Capsule extends Services_Capsule_Common
{
    /**
     * Sections available to the API
     *
     * @var array An array of sections available to the API
     */
    protected $sections = array();
    
    /**
     * Constructor
     *
     * Initialize the class with the API token.
     *
     * @param string $token  The API Token you use for your API Calls
     */
    public function __construct($appName, $token)
    {
        $this->token   = $token;
        $this->appName = $appName;
    }

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
            case 'Party':
            case 'Opportunity':
            case 'Kase':
            case 'Resource':
            case 'Person':

            if (!isset($this->sections[$section])) {
                $classname = 'Services_Capsule_' .$section;

                if (!class_exists($classname)) {
                    $filename  = str_replace('_', '/', $classname) . '.php';
                    
                    if (!(include $filename)) {
                        throw new Services_Capsule_RuntimeException(
                            'File ' . $filename . ' does not exist.'
                        );
                    }
                    
                }

                $this->sections[$section] = new $classname;
                
                $this->sections[$section]
                    ->setToken($this->token)
                    ->setAppName($this->appName)
                    ->setModuleName(strtolower($section));
            }
            
            return $this->sections[$section];
            break;

        default:
            throw new Services_Capsule_RuntimeException(
                'Section '. $section .' is not a valid API call. If you believe this ' . 
                'is wrong please report a bug on http://pear.php.net/Services_Capsule'
            );
        }
    }
}