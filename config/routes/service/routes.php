<?php
//List all users
$app->get("/service/addusers", "FriendsGraph\\Controller\\Service\\Data::generateUsers");