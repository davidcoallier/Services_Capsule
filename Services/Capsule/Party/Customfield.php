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
 * @link     http://capsulecrm.com/help/page/javelin_api_party_custom_fields
 * @version  Release: @package_version@
 */
class Services_Capsule_Party_Customfield extends Services_Capsule_Common
{
    
    /**
     * Get a list of custom fields
     *
     * List custom fields for a party. Note that boolean custom fields 
     * that have been set to false will not be returned. 
     *
     * @link    /api/party/{id}/customField
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party to retrieve 
     *                                     the custom field from.
     * @param  string       $fieldName     The custom field to retrieve.
     *
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */    
    public function get($partyId, $fieldName)
    {
        $url      = '/' . (double)$partyId . '/' . $fieldName;
        $response = $this->sendRequest($url);
        
        return $this->parseResponse($response);
    }
    
    /**
     * Get a list of available custom fields
     *
     * List of available custom field configurations for parties. 
     *
     * @link    /api/party/customfield/definitions
     * @throws Services_Capsule_RuntimeException
     * 
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getDefinitions()
    {
        $response = $this->sendRequest('/customfield/definitions');
        return $this->parseResponse($response);
    }
    
    /**
     * Add a new Custom field to a party
     *
     * This method is used to create a new custom field for a party.
     *
     * @link /api/party/{party-id}/customfield
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party id to create the new field on.
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function add($partyId, array $fields)
    {
        
        $url         = '/' . (double)$partyId . '/customfield';
        $customField = array('customField' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($customField)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Update a custom fieldof a party
     *
     * This method is used to update custom field of a party.
     *
     * Updating an existing boolean field to a value of false
     * will delete the custom field from the party, it will 
     * not be displayed on the next get. 
     *
     * @link   /api/party/{party-id}customfield/{customfield-id} 
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party id to update.
     * @param  double       $fieldId       The field id to update.
     * @param  array        $fields        An assoc array of fields to update in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function update($partyId, $fieldId, $fields)
    {
        $url         = '/' . (double)$partyId . '/customfield/ ' . (double)$fieldId;
        $customField = array('customField' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($customField)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Delete a custom field of a party
     *
     * This method is used to update an history note of a
     * party.
     *
     * @link   /api/party/{party-id}/customfield/{customfield-id} 
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $partyId       The party id to create the new field on.
     * @param  double       $fieldId
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function delete($partyId, $fieldId)
    {
        $url      = '/' . (double)$partyId . '/customfield/ ' . (double)$fieldId;
        $response = $this->sendRequest($url, HTTP_Request2::METHOD_DELETE);
        
        return $this->parseResponse($response);
    }
}
