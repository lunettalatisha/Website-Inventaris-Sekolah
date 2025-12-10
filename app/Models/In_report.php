<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class In_report extends Model
{
    use HasFactory;
}

class InventoryReport extends Model {
    protected $table = 'in_reports';
    protected $primaryKey = 'id_report';
    protected $fillable = ['id_admin','period','report_content'];

    public function admin(){ return $this->belongsTo(User::class,'id_admin','id'); }
}

