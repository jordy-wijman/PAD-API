<?php

namespace App\Custom;


class TileData
{
    public $notSmokedFor, $cigarettesSaved, $smokedToday, $savedMoney;

    public function setSavedMoney($money)
    {
        $this->savedMoney = "€" . number_format($money, 2);
    }

    public function getSavedMoney()
    {
        return $this->savedMoney;
    }
}