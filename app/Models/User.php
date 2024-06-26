<?php

namespace App\Models;

use App\Enums\ProjectType;
use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasUuids, Notifiable;

    protected $connection = 'pgsql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'default_project_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(fn (User $user) => $user->createPersonalProject());
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function defaultProject()
    {
        return $this->belongsTo(Project::class);
    }

    public function personalProject(): Project
    {
        return $this->projects()->where('type', ProjectType::Personal)->first();
    }

    public function createPersonalProject(): Project
    {
        $project = Project::create([
            'name' => $this->name,
            'type' => ProjectType::Personal,
        ]);

        $this->projects()->attach($project, ['role' => Role::Owner]);

        $this->defaultProject()->associate($project)->save();

        return $project;
    }
}
