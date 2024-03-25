<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImagesController extends Controller
{
    private function get_user_created_lists($uid) {
        return DB::table("touristlist")
            ->select("tlid")
            ->where("uid", [$uid])
            ->pluck("tlid")
            ->all();
    }
    private function get_journey_list($tlids) {
        return $this->get_all_datas_from_db_by_list("journey", "jid", "tlid", $tlids);
    }
    private function get_journey_project_list($jids) {
        return $this->get_all_datas_from_db_by_list("journeyproject", "jpid", "jid", $jids);;
    }
    /**
     * If you don't know how it works, think like this:
     *
     * ... SELECT $url_column ...
     * ... FROM TABLE $table ...
     * ... WHERE $id_column ...
     * ... IN ($id_array);
     */
    private function get_all_datas_from_db_by_list($table, $url_column, $id_column, $id_array) {
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
        $list_images = $this->get_all_datas_from_db_by_list("jimage", "jimg", "jid", $jlist);
        $project_list_images = $this->get_all_datas_from_db_by_list("jpimage", "jpimg", "jpid", $jplist);
        return [
            "message" => "success",
            "result" => array_merge($list_images, $project_list_images),
        ];
    }
    public function list_by_token() {
        $user = Auth::user();
        return response( $this->list_by_uid($user->id) );
    }
}
