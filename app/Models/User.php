<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'status' => 'boolean',
        ];
    }

    // Photo
    public static function uploadPhoto(UploadedFile $photo, string $nik): string
    {
        $filename = time().'-'.$nik.'.'.$photo->getClientOriginalExtension();
        $photo->storeAs('councils/photos', $filename, 'public');

        return $filename;
    }

    public function deletePhoto(): void
    {
        if ($this->photo && Storage::disk('public')->exists('councils/photos/'.$this->photo)) {
            Storage::disk('public')->delete('councils/photos/'.$this->photo);
        }
    }
}
