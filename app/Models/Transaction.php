<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account(){
        return $this->belongsTo(Account::class,'account_id');
    }
  
    public function person(){
        return $this->belongsTo(People::class,'person_id');
    }
  
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
  
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
}
