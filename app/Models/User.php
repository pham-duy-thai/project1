<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $bio
 * @property bool $is_admin
 * @property string $role (admin, student)
 * @property-read Student|null $student
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MaintenanceRequest> $assignedMaintenanceRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Violation> $reportedViolations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Announcement> $announcements
 * 
 * @method bool isAdmin()
 * @method bool isStudent()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'avatar',
        'phone',
        'address',
        'bio',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        // Return default if no avatar
        if (!$this->avatar) {
            return asset('assets/images/user/user-1.jpg');
        }
        
        // If it's already a full URL, return as is
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }
        
        // Handle paths starting with 'storage/' - already public path
        if (str_starts_with($this->avatar, 'storage/')) {
            $storagePath = str_replace('storage/', '', $this->avatar);
            if (Storage::disk('public')->exists($storagePath)) {
                return asset($this->avatar);
            }
            \Log::warning('User avatar not found', ['user_id' => $this->id, 'path' => $this->avatar]);
            return asset('assets/images/user/user-1.jpg');
        }
        
        // Handle paths starting with 'uploads/' - public directory
        if (str_starts_with($this->avatar, 'uploads/')) {
            if (file_exists(public_path($this->avatar))) {
                return asset($this->avatar);
            }
            \Log::warning('User avatar not found in public', ['user_id' => $this->id, 'path' => $this->avatar]);
            return asset('assets/images/user/user-1.jpg');
        }
        
        // Default: assume it's in storage/app/public (like 'avatars/xxx.jpg')
        if (Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        
        \Log::warning('User avatar not found in storage', ['user_id' => $this->id, 'path' => $this->avatar]);
        return asset('assets/images/user/user-1.jpg');
    }

    /**
     * Get student profile if user is a student
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get maintenance requests assigned to this user
     */
    public function assignedMaintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'assigned_to');
    }

    /**
     * Get violations reported by this user
     */
    public function reportedViolations()
    {
        return $this->hasMany(Violation::class, 'reported_by');
    }

    /**
     * Get announcements created by this user
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Get contracts signed by this user
     */
    public function signedContracts()
    {
        return $this->hasMany(Contract::class, 'signed_by');
    }

    /**
     * Get payments received by this user
     */
    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Quản trị viên',
            'student' => 'Sinh viên',
            default => 'Không xác định',
        };
    }
}
