<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    protected $fillable = ['item_name','quantity','condition','status','category_id','name','description','photo'];

    public function category() {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function borrowings() {
        return $this->hasMany(Borrowing::class,'item_id','id');
    }
}

