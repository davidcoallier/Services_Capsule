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
 *
 * @category  	Services
 * @package   	Services_Capsule
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
class Services_Capsule_Task extends Services_Capsule_Common
{
     /**
     * Get a Task
     *
     * This method is used to fetch a particular Task by
     * it's identification id.
     *
     * @link   /api/task/{task-id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $id The task ID to retrieve from the service.
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function get($id) 
    {        
        $response = $this->sendRequest('/' . (double)$id);
        return $this->parseResponse($response);
    }
    
    /**
     * List all the task information
     *
     * Retrieve a list of Tasks. Optionally the results can be 
     * limited or paged using the parameters $start and $limit
	 * {user} - filter the tasks by assigned user name
	 * {category} - filter tasks by assigned category
     *
     * @link  /api/tasks[?start={start}][&limit={limit}][&category={category}][&user={user}]
     * @throws Services_Capsule_RuntimeException
     *
     * @param  int $start  		The start page (Optional).
     * @param  int $limit  		The limit per page (Optional).
	 * @param  int $category 	The category to filter tasks by (Optional).
	 * @param  int $user		The user to filter tasks by (Optional).
     *
     * @return stdClass     A stdClass object containing the information from
     *                      the json-decoded response from the server.
     */
    public function getList($start = null, $limit = null, $category = null, $user = null)
    {
        $request = array();
        
        if (!is_null($start)) {
            $request['start'] = $start;
        }
        
        if (!is_null($limit)) {
            $request['limit'] = $limit;
        }
		
		if (!is_null($category)) {
			$request['category'] = $category;
		}
		
		if (!is_null($user)) {
			$request['user'] = $user;
		}
        
        $qs = http_build_query($request);
        $response = $this->sendRequest('s?' . $qs);
        return $this->parseResponse($response);
    }
    
    /**
     * Complete a Task
     *
     * Mark a task as complete
     * POST /api/task/{task-id}/complete
     *
     * @link   /api/task/{task-id}/complete
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $taskId       The task to be marked complete
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function complete($taskId)
    {
        $url = '/' . (double)$taskId . '/complete';
        $response = $this->sendRequest($url, HTTP_Request2::METHOD_POST);
        return $this->parseResponse($response);
    }
    
    /**
     * Re-open a Task
     *
     * Re-open a previously completed task
     * POST /api/task/{task-id}/reopen
     *
     * @link   /api/task/{task-id}/reopen
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $taskId       The task to be re-opened
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function reopen($taskId)
    {
        $url = '/' . (double)$taskId . '/repoen';
        $response = $this->sendRequest($url, HTTP_Request2::METHOD_POST);
        return $this->parseResponse($response);
    }
	
    /**
     * Add a task (unnattached)
     *
     * Create a new task not attached to a party, opp or case
     *
     * Example of input:
     *
     * <?php
     *     $fields = array(
     *         'description' => 'descrition of task',
     *         'dueDateTime'    => '2010-04-21T15:00:00Z', 
     *         ...
     *     );
     * ?>
     *
     * @link   /api/task
     * @throws Services_Capsule_RuntimeException
     *
     * @param  array        $fields        An array of fields to create the task with.
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
    public function add($fields)
    {
        $url         = '';
        $task = array('task' => $fields);
        
        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_POST, json_encode($task)
        );
        
        return $this->parseResponse($response);
    }
    
    /**
     * Update a task
     *
     * Update an existing task record, only attributes that 
     * are to be changed need to be supplied in the XML document. 
     *
     * Where a value is not supplied it will remain with its current value. 
     *
     * To null a field include a empty value for the field in the document.
     *
     * @link http://capsulecrm.com/help/page/api_task
     * @link /api/task/{task-id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $taskId			 The id of the task to update.
     * @param  array        $fields         An assoc array of fields to add in the task
     *
     * @return mixed bool|stdClass          A stdClass object containing the information from
     *                                      the json-decoded response from the server.
     */
    public function update($taskId, array $fields)
    {
        $url          = '/' . (double)$taskId;
        $task = array('task' => $fields);

        $response = $this->sendRequest(
            $url, HTTP_Request2::METHOD_PUT, json_encode($task)
        );
        
        return $this->parseResponse($response);
    }

    /**
     * Delete a task
     *
     * Delete the task according to its id.
     *
     * @link /api/task/{task-id}
     * @throws Services_Capsule_RuntimeException
     *
     * @param  double       $taskId        The task to delete.
     *
     * @return mixed bool|stdClass         A stdClass object containing the information from
     *                                     the json-decoded response from the server.
     */
     public function delete($taskId)
     {
         $url = '/' . (double)$taskId;
         $response = $this->sendRequest($url, HTTP_Request2::METHOD_DELETE);

         return $this->parseResponse($response);
     }

}