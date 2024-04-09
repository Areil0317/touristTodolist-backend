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

    public function update_list_image(Request $request)
    {

        $user = Auth::user();
        $tlid = $request->tlid;
        $model = ListModel::find($tlid);

        if( !isset($user) ) {
            return response([
                "message" => "No such user",
                "user" => $user
            ], 401);
        }
        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }
        if( $model->uid != $user->id ) {
            return response([
                "message" => "Unauthorised user",
                "user" => $user->id
            ], 401);
        }


        $request->validate([
            'tlphoto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('tlphoto')->store('images', 'public');
        $model->tlphoto = $path;


        $model->save();
        return response()->json(['message' => 'Data updated successfully',"result" => $model], 200);
    }



    public function add_jimage(Request $request)
    {
        $journeyId = $request->input('jid');

        if (!$journeyId) {
            return response()->json(['message' => 'Journey ID is required.'], 422);
        }

        $journey = JourneyModel::where('jid', $journeyId)
            ->whereHas('touristList', function ($query) use ($request) {
                $query->whereHas('user', function ($subQuery) use ($request) {
                    $subQuery->where('id', $request->user()->id);
                });
            })->first();

        if (!$journey) {
            return response()->json(['message' => 'Unauthorized or Journey not found.'], 403);
        }

        $request->validate([
            'jimg.*' => 'required|image|max:2048', // 修改了这里来验证多文件上传
        ]);

        $paths = []; // 存储所有图片路径的数组

        if ($request->hasFile('jimg')) {
            foreach ($request->file('jimg') as $file) { // 处理每个文件
                $path = $file->store('images', 'public');
                if ($path) {
                    $paths[] = $path; // 存储成功上传的文件路径

                    JimageModel::create([ // 使用 create 方法，确保模型中的 $fillable 属性已设置
                        'jid' => $journeyId,
                        'jimg' => $path,
                    ]);
                } else {
                    // 可以选择在这里处理文件存储失败的情况
                    return response()->json(['message' => 'Failed to store some images.'], 500);
                }
            }
        } else {
            return response()->json(['message' => 'No image provided.'], 422);
        }

        return response()->json(['message' => 'Images uploaded successfully.', 'paths' => $paths], 201);
    }


    public function add_jpimage(Request $request)
    {
        $jprojectId = $request->input('jpid'); // 从请求中获取 journeyproject 的 ID

        if (!$jprojectId) {
            return response()->json(['message' => 'JourneyProject ID is required.'], 422);
        }

        $journeyProject = JourneyProjectModel::where('jpid', $jprojectId)
            ->whereHas('journey.touristList', function ($query) use ($request) {
                $query->where('uid', $request->user()->id);
            })->first();

        if (!$journeyProject) {
            return response()->json(['message' => 'Unauthorized or JourneyProject not found.'], 403);
        }

        $request->validate([
            'jpimg.*' => 'required|image|max:2048',
        ]);

        $paths = [];

        if ($request->hasFile('jpimg')) {
            foreach ($request->file('jpimg') as $file) {
                $path = $file->store('images', 'public');
                if ($path) {
                    $paths[] = $path; // 收集成功存储的文件路径
                    JpimageModel::create([
                        'jpid' => $jprojectId,
                        'jpimg' => $path,
                    ]);
                } else {
                    return response()->json("Failed to store the file.");
                }
            }

            if (count($paths) > 0) {
                return response()->json(['message' => 'Images uploaded successfully.', 'paths' => $paths], 201);
            } else {
                return response()->json(['message' => 'Failed to upload images.'], 500);
            }
        } else {
            return response()->json(['message' => 'No image provided.'], 422);
        }
    }


}
