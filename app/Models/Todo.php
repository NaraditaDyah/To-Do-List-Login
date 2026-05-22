<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    // PERBAIKAN DI SINI: Daftarkan 'user_id' agar bisa disimpan ke database
    protected $fillable = [
        'user_id',
        'task_name',
        'deadline',
        'is_completed', // Pastikan ini juga ada jika digunakan
    ];

    /**
     * Relasi balik ke model User (Tugas ini dimiliki oleh siapa).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}