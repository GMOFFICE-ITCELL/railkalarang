<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;


class BookingForm extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps=false;
    protected $primaryKey = 'BF_id';

    protected $table = 'booking_form';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Mob_no',
        'Name',
    ];


    public static function folder()
    {
        return 'booking';
    }

    protected $appends = ['letter_head_url'];

    public function getLetterHeadUrlAttribute()
    {
        return $this->letter_head ? url('/').Storage::url($this->folder().'/'.$this->letter_head) : null;
    }



}
