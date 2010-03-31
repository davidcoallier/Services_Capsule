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
class Services_Capsule_Opportunity_Customfield extends Services_Capsule_Common
{
    
    /**
     * Get a list of custom fields
     *
     * List custom fields for a case. Note that boolean custom fields 
     * that have been set to false will not be returned. 
     *
     * @link    /api/opportunity/{id}/customfield
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $opportunityId The opportunity to retrieve 
     *                                     the custom field from.
     *
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */    
    public function getAll($opportunityId)
    {
        $url      = '/' . (double)$opportunityId . '/customfield' ;
        $response = $this->sendRequest($url);
        
        return $this->parseResponse($response);
    }
    
    /**
     * Get a list of available custom fields
     *
     * List of available custom field configurations for opportunities. 
     *
     * @link    /api/opportunity/customfield/definitions
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
     * Add a new Custom field to an opportunity (BY CASE ID)
     *
     * This method is used to create a new custom field to a
     * case that is associated in an opportunity.
     *
     * @link /api/opportunity/{kase-id}/customfield
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $caseId        The case id to create the new field on.
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function add($caseId, array $fields)
    {
        if (!isset($fields['boolean'])) {
            throw new Services_Capsule_RuntimeException(
                '"boolean" parameter of second parameter required ' . 
                'Ex: ("boolean" => "true")'
            );
        }
        
        $url         = '/' . (double)$caseId . '/customfield';
        $customField = array('customField' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($customField)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Update the custom field of an opportunity
     *
     * This method is used to update an history note to an
     * opportunity.
     *
     * Updating an existing boolean field to a value of false
     * will delete the custom field from the contact, it will 
     * not be displayed on the next get. 
     *
     * @link   /api/opportunity/{kase-id}/customfield/{customfield-id} 
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $caseId        The case id to create the new field on.
     * @param  double       $fieldId
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function update($caseId, $fieldId, $fields)
    {
        $url         = '/' . (double)$caseId . '/customfield/ ' . (double)$fieldId;
        $customField = array('customField' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($customField)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Delete the custom field of an opportunity
     *
     * This method is used to delete the custom field
     * of an opportunity.
     *
     * Updating an existing boolean field to a value of false
     * will delete the custom field from the contact, it will 
     * not be displayed on the next get. 
     *
     * @link   /api/opportunity/{kase-id}/customfield/{customfield-id} 
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $caseId        The case id to create the new field on.
     * @param  double       $fieldId
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     customField
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function delete($caseId, $fieldId)
    {
        $url      = '/' . (double)$caseId . '/customfield/ ' . (double)$fieldId;
        $response = $this->sendRequest($url, HTTP_Request2::METHOD_DELETE);
        
        return $this->parseResponse($response);
    }
}