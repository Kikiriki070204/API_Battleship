<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Gato extends Model
{
    protected $connection= 'mongodb';
    protected $collection = 'gatos';
}
