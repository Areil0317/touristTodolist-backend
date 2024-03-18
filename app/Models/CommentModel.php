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
    private function get_fbu_response_data($data, $uid) {
        // Get user
        $user = User::find($uid);
        $photo = $user->photo;
        // Array
        $result = array();
        foreach ($data as $key => $item) {
            $result[$key]["comment"] = $data[$key]["comment"];
            $result[$key]["rate"] = $data[$key]["rate"];
            $result[$key]["created_at"] = $data[$key]["created_at"];
            $result[$key]["pid"] = $data[$key]["pid"];
            $result[$key]["cid"] = $data[$key]["cid"];
            $data[$key]["photo"] = $photo;
        }
        return $data;
    }
    public function find_by_user($uid) {
        try {
            $sql = $this->where("uid", $uid)->get();
            $result = $this->get_fbu_response_data(
                $sql->select("cid", "comment", "rate", "created_at", "pid")->toArray(),
                $uid
            );
            return $result;
        } catch(\Exception $error) {
            return $error;
        }
    }

    private function get_fbp_response_data($data) {
        $result = array();
        foreach ($data as $key => $item) {
            $user = User::find($data[$key]["uid"]);
            $photo = $user->photo;
            $result[$key]["cid"] = $data[$key]["cid"];
            $result[$key]["comment"] = $data[$key]["comment"];
            $result[$key]["rate"] = $data[$key]["rate"];
            $result[$key]["created_at"] = $data[$key]["created_at"];
            $result[$key]["photo"] = $photo;
        }
        return $result;
    }

    public function find_by_project($pid) {
        try {
            $result = $this->where("pid", $pid)->get();
            // var_dump(  
            //     $this->get_fbp_response_data
            // );
            return $this->get_fbp_response_data(
                $result->select("cid", "comment", "rate", "created_at", "uid")->toArray()
            );
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
