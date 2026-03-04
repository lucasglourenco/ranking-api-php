<?php

namespace App\DAO;

use App\Database\BaseDAO;
use App\Database\Condition;

class PersonalRecordDAO extends BaseDAO
{
    protected string $table = 'personal_record pr';

    public function getRankingByMovement(int $movementId): array
    {
        return $this
            ->select('u.name', 'user_name')
            ->select('pr.value', 'personal_record')
            ->select('pr.date', 'record_date')
            ->select('RANK() OVER (ORDER BY pr.value DESC)', 'position')
            ->addJoin('user u', 'u.id = pr.user_id')
            ->where(
                Condition::where('pr.movement_id', '=', $movementId)
            )
            ->orderBy('position')
            ->get();
    }
}