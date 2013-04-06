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
 * | Contributor: J. Nolan <jeff@nolaninteractive.com>                     |
 * +-----------------------------------------------------------------------+
 *
 * PHP version 5
 * Services_Capsule
 *
 * @category  	Services
 * @author    	David Coallier <david@echolibre.com>
 * @contributor	Jeff Nolan <jeff@nolaninteractive.com>
 * @copyright 	echolibre ltd. 2009-2010
 * @license   	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @link      	http://github.com/davidcoallier/Services_Capsule
 * @version   	GIT: $Id$
 */

/**
 * Services_Capsule
 *
 * @category Services
 * @package  Services_Capsule
 * @author   David Coallier <david@echolibre.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @link     http://github.com/davidcoallier/Services_Capsule
 * @link     http://capsulecrm.com/help/page/javelin_api_party
 * @version  Release: @package_version@
 */
class Services_Capsule_Party_Cases extends Services_Capsule_Common
{
    /**
     * Get a list of party cases
     *
     * This method returns a list of cases associated
     * to a particular party.
     *
     * @link    /api/party/{id}/kase
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId  The party to retrieve the opportunities from.
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getAll($partyId)
    {
        $url      = '/' . (double)$partyId . '/kase';
        $response = $this->sendRequest($url);

        return $this->parseResponse($response);
    }
    
    /**
     * Add a new case to a party
     *
     * This method is used to create a new case for a party.
     *
     * @link /api/party/{party-id}/kase
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party id to create the new case in.
     * @param  array        $fields        An assoc array of fields to add in the new case
	 * @param  bool			$returnid      Indicates whether or not to return the newly created
	 *                                     case ID or the stdClass object.
     *
	 * @return mixed bool|stdClass|double  A stdClass object containing the information from
     *                                     the json-decoded response from the server,
	 *                                     Or the case ID (double)
     */
    public function add($partyId, array $fields, $returnid=false)
    {
        
        $url  = '/' . (double)$partyId . '/kase';
        $case = array('kase' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($case)
        );
        
		if ($returnid) {
			$header = $response->getHeader();
			return $this->getCaseIdFromString($header['location']);
		} else {
        	return $this->parseResponse($response);
		}
    }
    
	/**
     * Update the case of a party
     *
     * Update the case, if moving the case to 
     * another person or organisation use the alternative 
     * party version of the URL. case. 
     *
     * @link   /api/party/{party-id}/kase/{id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party id to create the update the case.
     * @param  double       $caseId        The case id to update.
     * @param  array        $fields        An assoc array of fields to update in the new
     *                                     case
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function update($partyId, $caseId, $fields)
    {
        $url  = '/' . (double)$partyId . '/kase/ ' . (double)$caseId;
        $case = array('kase' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($case)
        );
        
        return $this->parseResponse($response);
    }
	/**
     * Return the Case ID from the URL provided
     *
     * @param url string  $url  The URL that contains the case ID
     *
     * @return mixed int|string   Case ID number
     *
     */
	private function getCaseIdFromString($str="/")
	{
		$path = pathinfo($str);
		return (double)$path['basename'];
	}
}