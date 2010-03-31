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
class Services_Capsule_Person extends Services_Capsule_Common
{
    /**
     * Add a new Person to an organization
     *
     * Create a new person and optionally attach to an organisation. 
     * You can specify an organisation id or organisation name. 
     * If an organisation name is used an it is not already present on the 
     * account a new organisation will be created.
     * 
     * When adding addresses make the country element if used should be 
     * populated with a country returned from the resource GET /api/resource/country
     * or $capsule->resource->getCountries() or a ISO 3166-1 alternative.
     * 
     * If adding this person will exceed the accounts contact limit 
     * a 507 Insufficient Storage response will be returned.
     * 
     * @link http://capsulecrm.com/help/page/javelin_api_party
     * @link /api/person
     * @throws Services_Capsule_RuntimeException
     *
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     person
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function add(array $fields)
    {        
        $url         = '';
        $person = array('person' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($person)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Update a person
     *
     * Update an existing person record, only attributes that 
     * are to be changed need to be supplied in the XML document. 
     *
     * Where a value is not supplied it will remain with its current value. 
     *
     * To null a field include a empty element for the field in the document.
     *
     * Contact records (email, phone or address) can be updated where an id is 
     * provided, where an id for a contact is not provided it is assumed to be 
     * null and will be added to the person.
     *
     * @link http://capsulecrm.com/help/page/javelin_api_party
     * @link /api/organization
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $personId      The id of the person to update.
     * @param  array        $fields        An assoc array of fields to add in the new
     *                                     person
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function update($personId, array $fields)
    {
        $url         = '/' . (double)$personId;
        $person      = array('person' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($person)
        );
        
        return $this->parseResponse($response);
    }
}