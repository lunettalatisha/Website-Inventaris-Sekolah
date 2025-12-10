<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restore extends Model
{
    use HasFactory;
}

class RestoreRecord extends Model {
    protected $table = 'restore';
    protected $primaryKey = 'id_restores';
    protected $fillable = ['id_borrowing','actual_return_date','item_condition','restore_status'];

    public function borrowing(){ return $this->belongsTo(Borrowing::class,'id_borrowing','id_borrowing'); }
    public function fine(){ return $this->hasOne(Fine::class,'id_restore','id_restore'); }
}

