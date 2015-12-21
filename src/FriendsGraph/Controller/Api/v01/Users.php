<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Sazonov
 * Date: 18/12/2015
 * Time: 10:48
 */

namespace FriendsGraph\Controller\Api\v01;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use FriendsGraph\Controller\BaseController;
use FriendsGraph\Model\User;

class Users extends BaseController
{
    public function index(Request $request, Application $app)
    {
        $model = new User($app['neo']);
        $users = $model->getAll();

        if ($users) {
            return $this->sendJson($users, 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function create(Request $request, Application $app)
    {
        $model = new User($app['neo']);
        $user = [
            'id' => $model->generateId(),
            'name' => $request->get('name'),
            'age' => $request->get('age'),
            'country' => $request->get('country'),
            'countryCode' => $request->get('countryCode')
        ];

        if ($model->create($user)) {
            return $this->sendJson(['success' => true, 'id' => $user['id']], 200);
        } else {
            return $this->sendJson(['success' => false, 'id' => null], 404);
        }
    }

    public function update(Request $request, Application $app, $user_id)
    {
        $model = new User($app['neo']);
        $user = [
            'id' => $user_id,
            'name' => $request->get('name'),
            'age' => $request->get('age'),
            'country' => $request->get('country'),
            'countryCode' => $request->get('countryCode')
        ];

        if ($model->update($user)) {
            return $this->sendJson(['success' => true], 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function remove(Request $request, Application $app, $user_id)
    {
        $model = new User($app['neo']);

        if ($model->delete($user_id)) {
            return $this->sendJson(['success' => true], 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }

    public function getById(Request $request, Application $app, $user_id)
    {
        $model = new User($app['neo']);
        $user = $model->getById($user_id);

        if ($user) {
            return $this->sendJson($user, 200);
        } else {
            return $this->sendJson(['success' => false], 404);
        }
    }
}