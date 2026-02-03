<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'upload_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'upload_type',
        'file_name',
        'file_hash',
        'records_imported',
        'records_failed',
        'error_details',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'error_details' => 'array',
    ];

    /**
     * Check if a file has already been uploaded
     */
    public static function isFileUploaded($fileHash, $uploadType)
    {
        return self::where('file_hash', $fileHash)
            ->where('upload_type', $uploadType)
            ->exists();
    }
}
