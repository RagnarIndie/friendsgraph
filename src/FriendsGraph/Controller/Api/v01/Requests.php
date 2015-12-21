<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Sazonov
 * Date: 18/12/2015
 * Time: 10:48
 */

namespace FriendsGraph\Controller\Api\v01;

use FriendsGraph\Model\Friend;
use FriendsGraph\Model\FriendshipRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use FriendsGraph\Controller\BaseController;

class Requests extends BaseController
{
    public function index(Request $request, Application $app, $user_id)
    {
        $model = new FriendshipRequest($app['neo']);
        if ($allRequests = $model->getAllByUserId($user_id)) {
            return $this->sendJson($allRequests, 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function getById(Request $request, Application $app, $user_id, $request_id)
    {
        $model = new FriendshipRequest($app['neo']);
        if ($requestInfo = $model->getById($user_id, $request_id)) {
            return $this->sendJson($requestInfo, 200);
        } else {
            return $this->sendJson(['success' => false, 200]);
        }
    }

    public function add(Request $request, Application $app, $user_id)
    {
        $model = new FriendshipRequest($app['neo']);
        $friendReq = [
            'id' => $model->generateId(12),
            'from_user_id' => $user_id,
            'to_user_id' => $request->get('to_user_id'),
            'created' => time()
        ];

        if ($model->create($friendReq)) {
            return $this->sendJson(['success' => true, 'id' => $friendReq['id']], 200);
        } else {
            return $this->sendJson(['success' => false, 'id' => null], 404);
        }
    }

    public function accept(Request $request, Application $app, $user_id, $request_id)
    {
        $result = false;
        $requestModel = new FriendshipRequest($app['neo']);

        //Get friendship request data by request id
        $requestData = $requestModel->getById($user_id, $request_id);

        if ($requestData && isset($requestData['to']) && $user_id == $requestData['to']) {
            $friendModel = new Friend($app['neo']);

            //Trying to create bidirectional friendship relation
            if ($friendModel->create($user_id, $requestData['from'])) {
                //Remove friendship request
                $requestModel->delete($request_id);
                $result = true;
            }
        }

        if ($result) {
            return $this->sendJson(['success' => true], 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function decline(Request $request, Application $app, $user_id, $request_id)
    {
        $model = new FriendshipRequest($app['neo']);
        if ($model->delete($request_id)) {
            return $this->sendJson(['success' => true], 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }
}