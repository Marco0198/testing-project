<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @method static find(Task $task)
 */
class Task extends Model
{
    use SoftDeletes;
    use HasFactory;
    Protected  $fillable=['title','description', 'attach', 'status'];
    //protected $fillable = ['accepted'];



    /**
     *
     */

}
