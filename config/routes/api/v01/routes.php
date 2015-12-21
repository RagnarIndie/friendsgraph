<?php
$api_uri_version = "0.1";
$api_path_version = "v01";
$api_base_uri = "/api/{$api_uri_version}";
$api_controllers_path = "FriendsGraph\\Controller\\Api\\{$api_path_version}";

/*  USERS START */

//List all users
$app->get(
    "{$api_base_uri}/users",
    "{$api_controllers_path}\\Users::index"
);

//Create new user
$app->post(
    "{$api_base_uri}/users",
    "{$api_controllers_path}\\Users::create"
);

//Get user by id
$app->get(
    "{$api_base_uri}/users/{user_id}",
    "{$api_controllers_path}\\Users::getById"
);

//Update existing user by id
$app->put(
    "{$api_base_uri}/users/{user_id}",
    "{$api_controllers_path}\\Users::update"
);

//Remove existing user by id
$app->delete(
    "{$api_base_uri}/users/{user_id}",
    "{$api_controllers_path}\\Users::remove"
);
/*  USERS END */

/*  FRIENDS START */

//Get all friends for a specific user
$app->get(
    "{$api_base_uri}/users/{user_id}/friends",
    "{$api_controllers_path}\\Friends::index"
);

//Remove from user friends
$app->delete(
    "{$api_base_uri}/users/{user_id}/friends/{friend_id}",
    "{$api_controllers_path}\\Friends::remove"
);
/*  FRIENDS END */

/*  REQUESTS START */

//Get all user requests
$app->get(
    "{$api_base_uri}/users/{user_id}/requests",
    "{$api_controllers_path}\\Requests::index"
);

//Add new friendship request
$app->post(
    "{$api_base_uri}/users/{user_id}/requests",
    "{$api_controllers_path}\\Requests::add"
);

//Get request by id
$app->get(
    "{$api_base_uri}/users/{user_id}/requests/{request_id}",
    "{$api_controllers_path}\\Requests::getById"
);

//Accept specific request
$app->put(
    "{$api_base_uri}/users/{user_id}/requests/{request_id}",
    "{$api_controllers_path}\\Requests::accept"
);

//Decline specific request
$app->delete(
    "{$api_base_uri}/users/{user_id}/requests/{request_id}",
    "{$api_controllers_path}\\Requests::decline"
);
/*  REQUESTS END */