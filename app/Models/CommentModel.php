<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = "comments";
    protected $primaryKey = "cid";

    protected $fillable = [
        "uid",
        "pid",
        "comment",
        "rate",
    ];

    public $incrementing = false;
    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }
    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ["cid"];
    }

    // Custom API props
    public function find_by_user($uid) {
        try {
            $result = $this->where("uid", $uid)->get();
            return $result;
        } catch(\Exception $error) {
            return $error;
        }
    }

    public function find_by_project($pid) {
        try {
            $result = $this->where("pid", $pid)->get();
            return $result;
        } catch(\Exception $error) {
            return $error;
        }
    }

    /**
     * Get the comment's changelog.
     */
    public function comment_histroy()
    {
        return $this->comment_histroy_source()->select("before", "after")->get();
    }

    // API prop metadatas
    /**
     * Get the user's metadata.
     */
    public function user()
    {
        return $this->belongsTo(User::class, "uid", "id");
    }

    /**
     * Get the commentlog's metadata.
     */
    public function comment_histroy_source(): HasMany
    {
        return $this->hasMany(CommentChangelog::class, "cid");
    }

    public function userdata()
    {
        $source = $this->user()->select("id", "name", "email", "photo");
        return $source;
    }
}
