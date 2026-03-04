<?php

namespace App\Controllers;

use App\DAO\MovementDAO;
use App\DAO\PersonalRecordDAO;
use App\Enums\HttpStatus;
use App\Http\JsonResponse;

class RankingController
{
    public function show($request, array $args): \Nyholm\Psr7\Response
    {
        $identifier = $args['movement'] ?? null;

        if (!$identifier) {
            return JsonResponse::fail(HttpStatus::BAD_REQUEST);
        }

        $movementDao = new MovementDAO();
        $recordDao = new PersonalRecordDAO();

        $movement = is_numeric($identifier)
            ? $movementDao->findById((int)$identifier)
            : $movementDao->findByName($identifier);

        if (!$movement) {
            return JsonResponse::fail(HttpStatus::NOT_FOUND);
        }

        $ranking = $recordDao->getRankingByMovement($movement['id']);

        foreach ($ranking as &$row) {
            $row = [
                'position' => $row['position'],
                'user' => $row['user_name'],
                'personal_record' => (float)$row['personal_record'],
                'date' => $row['record_date'],
            ];
        }

        return JsonResponse::success(
            HttpStatus::OK,
            [
                'movement' => $movement['name'],
                'ranking' => $ranking
            ]
        );
    }
}