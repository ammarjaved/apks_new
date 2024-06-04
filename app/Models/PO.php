<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    use HasFactory;
    public $table = 'po_detail';
    protected $fillable = ['id', 'vendor_name', 'po_name', 'po_no', 'created_at', 'create_by','zone','ba'];

}
