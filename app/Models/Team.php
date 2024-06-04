<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'tbl_team';
    protected $fillable = ['team_name' , 'team_type'];
    public $timestamps = false;

    public function teamUsers(){

            return $this->hasMany(User::class, 'id_team');

    }
}
