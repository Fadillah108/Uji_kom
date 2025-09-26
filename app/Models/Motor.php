<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilik_id','merk','tipe_cc','no_plat','status','is_approved','approved_at','photo','dokumen_kepemilikan','deskripsi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function tarifRental()
    {
        return $this->hasOne(TarifRental::class, 'motor_id');
    }

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }
}
