<?php
//Web routes
require_once __DIR__.'/web/routes.php';

//Service routes
require_once __DIR__.'/service/routes.php';

//API routes
require_once __DIR__.'/api/v01/routes.php'; #Version 0.1

$app->error(function (\Exception $e, $code) {
    return new \Symfony\Component\HttpFoundation\Response(json_encode(['success' => false]), 404);
});