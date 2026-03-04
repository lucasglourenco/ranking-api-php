<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {

    $r->addRoute(
        'GET',
        '/ranking/{movement}',
        [\App\Controllers\RankingController::class, 'show']
    );
};