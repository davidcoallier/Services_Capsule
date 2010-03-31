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
 * @link     http://capsulecrm.com/help/page/javelin_api_opportunity
 * @version  Release: @package_version@
 */
class Services_Capsule_Opportunity extends Services_Capsule_Common
{
    /**
     * Get an opportunity
     *
     * This method is used to fetch a particular opportunity by
     * it's identification id.
     *
     * @link   /api/opportunity/{id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $id The opportunity ID to retrieve from the service.
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function get($id) 
    {        
        $response = $this->sendRequest('/' . (double)$id);
        return $this->parseResponse($response);
    }
    
    /**
     * List all the opportunities
     *
     * List opportunities. Optionally the results can be 
     * limited or paged using the parameters $limit and $start. 
     *
     * @link  /api/opportunity[?start={start}][&limit={limit}] 
     * @throws Services_Capsule_RuntimeException
     *
     * @param  int $start  The start page (Optional).
     * @param  int $limit  The limit per page (Optional).
     *
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getList($start = null, $limit = null)
    {
        $request = array();
        
        if (!is_null($start)) {
            $request['start'] = $start;
        }
        
        if (!is_null($limit)) {
            $request['limit'] = $limit;
        }
        
        $request = http_build_query($request);
        $response = $this->sendRequest('?' . $request);
        return $this->parseResponse($response);
    }
    
    /**
     * Get any opportunities
     *
     * This method fetches a list of opportunities for a company (appName)
     * by tag, milestone (name), or lastmodified field with the usual
     * start and limit tag.
     *
     * If you are fetching by lastmodified, you must make sure that the input
     * is formatted as ISO dates (IE: Midnight June 31, 2009 GMT would be 20090631T000000)
     * or more explicitly YYYYMMDDTHHMMSS
     * 
     * Example:
     * <?php
     *      try {
     *          $capsule = new Services_Capsule($appName, $token);
     *          $results = $capsule->opportunity->getAny(array(
     *              'lastmodified' => '20090631T000000',
     *              'start'        => '100',
     *              'limit'        => '25'
     *          ));
     *      } catch (Services_Capsule_RuntimeException $re) {
     *          print_r($re); die();
     *      }
     *
     *      print_r($results); // An object
     * ?>
     *
     * @link   /api/opportunity?lastmodified={YYYYMMDDTHHMMSS}[&start={start}][&limit={limit}]
     * @link   /api/opportunity?tag={tag}[&start={start}][&limit={limit}]
     *
     * @throws Services_Capsule_RuntimeException
     *
     * @param  array        $params An array of parameters to search for.
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getAny(array $params)
    {        
        $request  = http_build_query($params);
        $response = $this->sendRequest('?' . $request);

        return $this->parseResponse($response);
    }
    
    /**
     * Delete an opportunity
     *
     * Delete the opportunity passed to the method.
     *
     * @link /api/opportunity/{opportunity-id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $opportunityId The opportunity to delete.
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
     public function delete($opportunityId)
     {
         $url = '/' . (double)$opportunityId;
         $response = $this->sendRequest($url, HTTP_Request2::METHOD_DELETE);

         return $this->parseResponse($response);
     }

}