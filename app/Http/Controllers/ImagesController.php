<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImagesController extends Controller
{
    private function get_user_created_lists($uid) {
        return DB::table("touristlist")
            ->select("tlid")->where("uid", [$uid])
            ->pluck("tlid")
            ->all();
    }
    private function get_journey_list($tlids) {
        $sql = DB::table("journey")
            ->select("jid")
            ->whereIn("tlid", $tlids)
            ->pluck("jid")
            ->all();
        return $sql;
    }
    private function get_journey_project_list($jids) {
        $sql = DB::table("journeyproject")
            ->select("jpid")
            ->whereIn("jid", $jids)
            ->pluck("jpid")
            ->all();
        return $sql;
    }
    /**
     * If you don't know how it works, think like this:
     *
     * ... SELECT $url_column ...
     * ... FROM TABLE $table ...
     * ... WHERE $id_column ...
     * ... IN ($id_array);
     */
    private function get_images_from_db($table, $url_column, $id_column, $id_array) {
        $sql = DB::table($table)
            ->select($url_column)
            ->whereIn($id_column, $id_array)
            ->pluck($url_column)
            ->all();
        return $sql;
    }
    public function list_by_uid($uid) {
        // List all IDs
        $list = $this->get_user_created_lists($uid);
        $jlist = $this->get_journey_list($list);
        $jplist = $this->get_journey_project_list($jlist);

        // List all images
        $list_images = $this->get_images_from_db("jimage", "jimg", "jid", $jlist);
        $project_list_images = $this->get_images_from_db("jpimage", "jpimg", "jpid", $jplist);
        return [
            "message" => "success",
            "list" => array_merge($list_images, $project_list_images),
        ];
    }
    public function list_by_token() {
        $user = Auth::user();
        return response( $this->list_by_uid($user->id) );
    }
}
