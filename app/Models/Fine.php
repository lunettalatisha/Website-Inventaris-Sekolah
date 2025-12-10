<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;
}

class Fine extends Model {
    protected $primaryKey = 'id_fine';
    protected $fillable = ['id_return','amount','payment_status'];

    public function restoreRecord(){ return $this->belongsTo(RestoreRecord::class,'id_restore','id_restore'); }
}

