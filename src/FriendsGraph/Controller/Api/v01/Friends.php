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

class Friends extends BaseController
{
    public function index(Request $request, Application $app, $user_id)
    {
        $level = ($request->get('level') == null) ? 0 : intval($request->get('level'));
        $model = new Friend($app['neo']);

        if ($friends = $model->getAllByUserId($user_id, $level)) {
            return $this->sendJson($friends, 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function remove(Request $request, Application $app, $user_id, $friend_id)
    {
        $model = new Friend($app['neo']);

        if ($model->delete($user_id, $friend_id)) {
            return $this->sendJson(['success' => true], 200);
        } else {
            return $this->sendJson(['seccess' => false], 404);
        }
    }
}