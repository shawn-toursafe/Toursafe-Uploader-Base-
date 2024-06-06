<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'archive_id',
        'archive_url',
        'file_ext',
        'file_type',
        'size',
        'archive_type',
        'subscription',
        'downloadable',
        'download_requested',
        'valid_until',
    ];
}
