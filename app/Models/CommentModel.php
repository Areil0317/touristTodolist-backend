<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;
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

    /**
     * API formation: Format comment data for API response.
     */
    private function format_api_response($comment, $user)
    {
        $photo = isset($user) ? $user->getPhotoUrlAttribute() : "";
        $username = isset($user) ? $user->name : "";
        return [
            'cid' => $comment->cid,
            'uid' => $comment->uid,
            'pid' => $comment->pid,
            'username' => $username,
            'comment' => $comment->comment,
            'rate' => $comment->rate,
            'created_at' => $comment->created_at,
            'photo' => $photo,
        ];
    }

    public static function comment_api_item_formation($comment)
    {
        $user = $comment["userdata"];
        $photo = $user["photo"];
        $username = $user["name"];
        return [
            'cid' => $comment->cid,
            'uid' => $comment->uid,
            'pid' => $comment->pid,
            'username' => $username,
            'comment' => $comment->comment,
            'rate' => $comment->rate,
            'created_at' => $comment->created_at,
            'photo' => $photo,
        ];
    }

    /**
     * Retrieve comments by user ID.
     */
    public function find_by_user($uid) {
        try {
            $user = User::find($uid);
            $comments = $this->where("uid", $uid)->get();
            return $comments->map(function ($comment) use ($user) {
                return $this->format_api_response($comment, $user);
            })->all();
        } catch(\Exception $error) {
            return $error->getMessage();
        }
    }

    public function find_by_project($pid) {
        try {
            $comments = $this->where("pid", $pid)->get();
            return $comments->map(function ($comment) {
                $user = User::find($comment["uid"]);
                return $this->format_api_response($comment, $user);
            })->all();
        } catch(\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * Get the commentlog's metadata.
     */
    public function comment_histroy_source(): HasMany
    {
        return $this->hasMany(CommentChangelog::class, "cid");
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

    public function userdata()
    {
        $source = $this->user()->select("id", "name", "email", "photo");
        return $source;
    }
}
