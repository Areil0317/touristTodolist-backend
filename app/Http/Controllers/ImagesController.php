<?php

namespace App\Http\Controllers;

use App\Models\JimageModel;
use App\Models\JourneyModel;
use App\Models\JourneyProjectModel;
use App\Models\JpimageModel;
use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except([
            "list_by_uid"
        ]);
    }
    private function get_user_created_lists($uid)
    {
        return DB::table("touristlist")
            ->select("tlid")
            ->where("uid", [$uid])
            ->pluck("tlid")
            ->all();
    }
    private function get_journey_list($tlids)
    {
        return $this->get_all_datas_from_db_by_list("journey", "jid", "tlid", $tlids);
    }
    private function get_journey_project_list($jids)
    {
        return $this->get_all_datas_from_db_by_list("journeyproject", "jpid", "jid", $jids);
        ;
    }
    /**
     * If you don't know how it works, think like this:
     *
     * ... SELECT $url_column ...
     * ... FROM TABLE $table ...
     * ... WHERE $id_column ...
     * ... IN ($id_array);
     */
    private function get_all_datas_from_db_by_list($table, $url_column, $id_column, $id_array)
    {
        $sql = DB::table($table)
            ->select($url_column)
            ->whereIn($id_column, $id_array)
            ->pluck($url_column)
            ->all();
        return $sql;
    }
    public function list_by_uid($uid)
    {
        // List all IDs
        $list = $this->get_user_created_lists($uid);
        $jlist = $this->get_journey_list($list);
        $jplist = $this->get_journey_project_list($jlist);

        // List all images
        $list_images = $this->get_all_datas_from_db_by_list("jimage", "jimg", "jid", $jlist);
        $project_list_images = $this->get_all_datas_from_db_by_list("jpimage", "jpimg", "jpid", $jplist);
        $result = array_merge($list_images, $project_list_images);
        return [
            "message" => count($result) > 0 ? "success" : "no images",
            "result" => array_merge($list_images, $project_list_images),
        ];
    }
    public function list_by_token()
    {
        $user = Auth::user();
        return response($this->list_by_uid($user->id));
    }



    public function add_jimage(Request $request)
    {
        $journeyId = $request->input('jid');

        if (!$journeyId) {
            return response()->json(['message' => 'Journey ID is required.'], 422);
        }

        // 验证当前用户是否有权限向该Journey添加图片
        $journey = JourneyModel::where('jid', $journeyId)
            ->whereHas('touristList', function ($query) use ($request) {
                $query->whereHas('user', function ($subQuery) use ($request) {
                    $subQuery->where('id', $request->user()->id);
                });
            })->first();

        if (!$journey) {
            return response()->json(['message' => 'Unauthorized or Journey not found.'], 403);
        }

        // 验证请求中的文件
        $request->validate([
            'jimg' => 'required|image|max:2048',
        ]);

        // 存储图片
        $path = $request->file('jimg')->store('images', 'public');

        // 创建并保存图片信息到jimage表
        $jimage = new JimageModel([
            'jid' => $journeyId,
            'jimg' => $path,
        ]);
        $jimage->save();

        return response()->json(['message' => 'Image uploaded successfully.', 'path' => $path], 201);
    }

    public function add_jpimage(Request $request)
    {
        $jprojectId = $request->input('jpid'); // 从请求中获取 journeyproject 的 ID

        if (!$jprojectId) {
            return response()->json(['message' => 'JourneyProject ID is required.'], 422);
        }

        // 验证当前用户是否有权限向该JourneyProject添加图片
        $journeyProject = JourneyProjectModel::where('jpid', $jprojectId)
            ->whereHas('journey', function ($query) use ($request) {
                $query->whereHas('touristList', function ($subQuery) use ($request) {
                    $subQuery->where('uid', $request->user()->id);
                });
            })->first();

        if (!$journeyProject) {
            return response()->json(['message' => 'Unauthorized or JourneyProject not found.'], 403);
        }

        // 验证请求中的文件
        $request->validate([
            'jpimg' => 'required|image|max:2048',
        ]);

        // 存储图片
        $path = $request->file('jpimg')->store('images', 'public');

        // 创建并保存图片信息到jpimage表
        $jpImage = new JpimageModel([
            'jpid' => $jprojectId,
            'jpimg' => $path,
        ]);
        $jpImage->save();

        return response()->json(['message' => 'Image uploaded successfully.', 'path' => $path], 201);
    }


}
