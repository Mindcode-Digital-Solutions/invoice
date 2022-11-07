<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function accountsTransactions(){
        return $this->hasMany(AccountsTransaction::class,'account_id');
    }
   
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }

}
