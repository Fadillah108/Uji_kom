<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifRental extends Model
{
    use HasFactory;

    protected $fillable = ['motor_id','tarif_harian','tarif_mingguan','tarif_bulanan'];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
