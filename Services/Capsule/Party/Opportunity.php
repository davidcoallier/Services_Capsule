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
 * @link     http://capsulecrm.com/help/page/javelin_api_party
 * @version  Release: @package_version@
 */
class Services_Capsule_Party_Opportunity extends Services_Capsule_Common
{

    /**
     * Get a list of party opportunities
     *
     * This method returns a list of opportunities associated
     * to a particular party.
     *
     * @link    /api/party/{id}/opportunity
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId  The party to retrieve the opportunities from.
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getAll($partyId)
    {
        $url      = '/' . (double)$partyId . '/opportunity';
        $response = $this->sendRequest($url);

        return $this->parseResponse($response);
    }

    /**
     * Add a opportunity to a party
     *
     * Create a new opportunity attached to this person or organisation, when 
     * creating an opportunity for a person attached to an organisation the person 
     * is added the additional contact list for this opportunity 
     * GET /api/opportunity/{id}/party
     *
     * Example of input:
     *
     * <?php
     *     $fields = array(
     *         'name' => 'Testing adding opportunity',
     *         'description' => 'descrition of opptu',
     *         'currency'    => 'GBP', // See resource $capsule->resource->getCurrencies()
     *         ...
     *     );
     * ?>
     *
     * @link   /api/party/{party-id}/opportunity
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party/org to add to the opportunity.
     * @param  array        $fields        An array of fields to create the opp with.
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function add($partyId, $fields)
    {
        $url         = '/' . (double)$partyId . '/opportunity';
        $opportunity = array('opportunity' => $fields);
        
        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($opportunity)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Update an opportunity of a party
     *
     * Update said opportunity for a party.
     *
     * Example of input:
     * <?php
     *     $fields = array(
     *         'milestone' => 'Won',
     *     );
     * ?>
     *
     * @link   /api/party/{party-id}/opportunity
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party/org to update the opportunity.
     * @param  array        $fields        An array of fields to update the opp with.
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function update($partyId, $fields)
    {
        $url         = '/' . (double)$partyId . '/opportunity';
        $opportunity = array('opportunity' => $fields);
        
        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($opportunity)
        );
        
        return $this->parseResponse($response);
    }
}