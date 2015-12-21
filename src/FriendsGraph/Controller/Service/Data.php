<?php
namespace FriendsGraph\Controller\Service;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use FriendsGraph\Controller\BaseController;
use FriendsGraph\Model\User;

class Data extends BaseController
{
    public function generateUsers(Request $request, Application $app)
    {
        $countries = require_once(DATA_DIR.'countries.php');
        $names = array_map(function($el) {
            $parts = explode(' ', $el);
            return "{$parts[0]} {$parts[1]}";
        }, file(DATA_DIR.'names.txt', FILE_IGNORE_NEW_LINES));

        $model = new User($app['neo']);
        $results = $model->generate($names, $countries);

        return $this->sendJson(['success' => true], 200);
    }
}