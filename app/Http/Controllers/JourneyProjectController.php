<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JourneyModel;
use App\Models\AttractionModel;
use App\Models\JourneyProjectModel;
use App\Models\ProjectModel;
use App\Models\JpbudgetModel;
use App\Models\JpimageModel;

class JourneyProjectController extends Controller
{
    public function addJourneyProject_post(Request $request)
    {

        $model = new JourneyProjectModel;
        $jid = $request->jid;
        $journey = JourneyModel::find($jid);

        if (!$journey) {
            return response()->json(['message' => 'Data not found'], 404);
        }


        $aid = $journey->aid;

        if($request->pname == null){
            $pname = "請輸入行程項目名稱";
        }else{
            $pname = $request->pname;
        }
        $project = ProjectModel::firstOrCreate(['pname' => $pname, 'aid' => $aid]);

        $model->jid = $journey->jid;
        $model->pid = $project->pid;
        $arrivedDate = strtotime($journey->arrived_date);
        $leavedDate = strtotime($journey->leaved_date);
        $jpStartDate = strtotime($request->jpstart_date);
        $jpEndDate = strtotime($request->jpend_date);



        if ($request->jpstart_date == null) {
            $model->jpstart_date = $journey->arrived_date;
        } else {

            if ($arrivedDate <= $jpStartDate && $jpStartDate <= $leavedDate) {
                $model->jpstart_date = $request->jpstart_date;
            } else {
                if ($jpStartDate > $leavedDate) {
                    $model->jpstart_date = $journey->leaved_date;
                } else {
                    $model->jpstart_date = $journey->arrived_date;
                }
            }
        }

        if ($request->jpend_date == null) {
            $model->jpend_date = $model->jpstart_date;
        } else {
            if ($arrivedDate <= $jpEndDate && $jpEndDate <= $leavedDate) {
                $model->jpend_date = $request->jpend_date;
            } else {
                $jpStartDate = strtotime($model->jpstart_date);
                if ($jpEndDate < $jpStartDate) {
                    $model->jpend_date = $model->jpstart_date;
                } else {
                    $model->jpend_date = $journey->leaved_date;
                }
            }
        }

        $arrivedTime = strtotime($journey->arrived_time);
        $leavedTime = strtotime($journey->leaved_time);
        $jpStartTime = strtotime($request->jpstart_time);
        $jpEndTime = strtotime($request->jpend_time);

        $jpStartDate = strtotime($model->jpstart_date);
        $jpEndDate = strtotime($model->jpend_date);



        if ($arrivedTime != null && $jpStartTime != null) {
            if ($arrivedTime <= $jpStartTime) {
                if ($leavedTime != null && $leavedTime < $jpStartTime) {
                    if ($leavedDate != $jpStartDate) {
                        $model->jpstart_time = $request->jpstart_time;
                    } else {
                        $model->jpstart_time = $journey->leaved_time;
                    }
                } else {
                    $model->jpstart_time = $request->jpstart_time;
                }   
            } else {
                if ($arrivedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->arrived_time;
                }
            }
        } else {
            if ( $leavedTime != null && $jpStartTime != null && $leavedTime < $jpStartTime) {
                if ($leavedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->leaved_time;
                }
            } else {
                $model->jpstart_time = $request->jpstart_time;
            }
        }


        if ( $leavedTime != null && $jpEndTime != null ) {
            if ($leavedTime >= $jpEndTime) {
                if ( $arrivedTime != null && $arrivedTime > $jpEndTime) {
                    if ($arrivedDate != $jpEndDate) {
                        $model->jpend_time = $request->jpend_time;
                    } else {
                        $model->jpend_time = $journey->arrived_time;
                    }
                } else {
                    $model->jpend_time = $request->jpend_time;
                }  
            } else {
                if ($leavedDate != $jpEndDate) {
                    $model->jpend_time = $request->jpend_time;
                } else {
                    $model->jpend_time = $journey->leaved_time;
                }
            }
        } else {
            if ( $arrivedTime != null && $jpEndTime != null && $arrivedTime > $jpEndTime) {
                if ($arrivedDate != $jpEndDate) {
                    $model->jpend_time = $request->jpend_time;
                } else {
                    $model->jpend_time = $journey->arrived_time;
                }
            } else {
                $model->jpend_time = $request->jpend_time;
            }
        }


        $jpStartTime = strtotime($model->jpstart_time);
        $jpEndTime = strtotime($model->jpend_time);
        if($model->jpstart_time == null){
            $model->jpstart_time = $journey->arrived_time;
        }


        if ($jpEndDate <= $jpStartDate) {
            $model->jpend_date = $model->jpstart_date;
            if (
                $jpEndTime < $jpStartTime
                && $model->jpend_time != null
                && $model->jpstart_time != null
            ) {
                $model->jpend_time = $model->jpstart_time;
            }
        }

        $model->jpmemo = $request->jpmemo;
        $model->jpreview = $request->jpreview;
        $model->jprate = $request->jprate;

        if (!isset ($request->jpchecked)) {
            $model->jpchecked = "0";
        } else {
            $model->jpchecked = $request->jpchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data created successfully'], 201);
    }


    public function deleteJourneyProject_post(Request $request)
    {

        $jpid = $request->jpid;
        $model = JourneyProjectModel::find($jpid);
        $model->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);
    }


    public function updateJourneyProject_post(Request $request)
    {

        $jpid = $request->jpid;
        $model = JourneyProjectModel::find($jpid);

        if(isset($request->jid) && ($model->jid != $request->jid) ){
            $jid = $request->jid;
        }else{
            $jid = $model->jid;
        }
        
        $journey = JourneyModel::find($jid);

        if (!$journey) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $aid = $journey->aid;

        if($request->pname == null){
            $pname = "請輸入行程項目名稱";
        }else{
            $pname = $request->pname;
        }
        $project = ProjectModel::firstOrCreate(['pname' => $pname, 'aid' => $aid]);

        $model->jid = $journey->jid;
        $model->pid = $project->pid;

        $arrivedDate = strtotime($journey->arrived_date);
        $leavedDate = strtotime($journey->leaved_date);
        $jpStartDate = strtotime($request->jpstart_date);
        $jpEndDate = strtotime($request->jpend_date);

        if ($request->jpstart_date == null) {
            $model->jpstart_date = $journey->arrived_date;
        } else {

            if ($arrivedDate <= $jpStartDate && $jpStartDate <= $leavedDate) {
                $model->jpstart_date = $request->jpstart_date;
            } else {
                if ($jpStartDate > $leavedDate) {
                    $model->jpstart_date = $journey->leaved_date;
                } else {
                    $model->jpstart_date = $journey->arrived_date;
                }
            }
        }

        if ($request->jpend_date == null) {
            $model->jpend_date = $model->jpstart_date;
        } else {
            if ($arrivedDate <= $jpEndDate && $jpEndDate <= $leavedDate) {
                $model->jpend_date = $request->jpend_date;
            } else {
                $jpStartDate = strtotime($model->jpstart_date);
                if ($jpEndDate < $jpStartDate) {
                    $model->jpend_date = $model->jpstart_date;
                } else {
                    $model->jpend_date = $journey->leaved_date;
                }
            }
        }

        $arrivedTime = strtotime($journey->arrived_time);
        $leavedTime = strtotime($journey->leaved_time);
        $jpStartTime = strtotime($request->jpstart_time);
        $jpEndTime = strtotime($request->jpend_time);


        $jpStartDate = strtotime($model->jpstart_date);
        $jpEndDate = strtotime($model->jpend_date);



        if ($arrivedTime != null && $jpStartTime != null) {
            if ($arrivedTime <= $jpStartTime) {
                if ($leavedTime != null && $leavedTime < $jpStartTime) {
                    if ($leavedDate != $jpStartDate) {
                        $model->jpstart_time = $request->jpstart_time;
                    } else {
                        $model->jpstart_time = $journey->leaved_time;
                    }
                } else {
                    $model->jpstart_time = $request->jpstart_time;
                }   
            } else {
                if ($arrivedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->arrived_time;
                }
            }
        } else {
            if ( $leavedTime != null && $jpStartTime != null && $leavedTime < $jpStartTime) {
                if ($leavedDate != $jpStartDate) {
                    $model->jpstart_time = $request->jpstart_time;
                } else {
                    $model->jpstart_time = $journey->leaved_time;
                }
            } else {
                $model->jpstart_time = $request->jpstart_time;
            }
        }


        if ( $leavedTime != null && $jpEndTime != null ) {
            if ($leavedTime >= $jpEndTime) {
                if ( $arrivedTime != null && $arrivedTime > $jpEndTime) {
                    if ($arrivedDate != $jpEndDate) {
                        $model->jpend_time = $request->jpend_time;
                    } else {
                        $model->jpend_time = $journey->arrived_time;
                    }
                } else {
                    $model->jpend_time = $request->jpend_time;
                }  
            } else {
                if ($leavedDate != $jpEndDate) {
                    $model->jpend_time = $request->jpend_time;
                } else {
                    $model->jpend_time = $journey->leaved_time;
                }
            }
        } else {
            if ( $arrivedTime != null && $jpEndTime != null && $arrivedTime > $jpEndTime) {
                if ($arrivedDate != $jpEndDate) {
                    $model->jpend_time = $request->jpend_time;
                } else {
                    $model->jpend_time = $journey->arrived_time;
                }
            } else {
                $model->jpend_time = $request->jpend_time;
            }
        }


        $jpStartTime = strtotime($model->jpstart_time);
        $jpEndTime = strtotime($model->jpend_time);

        if($model->jpstart_time == null){
            $model->jpstart_time = $journey->arrived_time;
        }

        if ($jpEndDate <= $jpStartDate) {
            $model->jpend_date = $model->jpstart_date;
            if (
                $jpEndTime < $jpStartTime
                && $model->jpend_time != null
                && $model->jpstart_time != null
            ) {
                $model->jpend_time = $model->jpstart_time;
            }
        }

        $model->jpmemo = $request->jpmemo;
        $model->jpreview = $request->jpreview;
        $model->jprate = $request->jprate;

        if (!isset ($request->jpchecked)) {
            $model->jpchecked = "0";
        } else {
            $model->jpchecked = $request->jpchecked;
        }

        $model->save();

        return response()->json(['message' => 'Data updated successfully'], 200);
    }


    public function selectJourneyProject_post(Request $request)
    {
        $jid = $request->jid;
        $model = JourneyProjectModel::where('jid', $jid)->get();
        
        if (!$model) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($model, 200);
    }

    public function addJpbudget_post(Request $request)
    {

        if ($request->jpbamount != null || $request->jpbname != null) {

            $jpid = $request->jpid;
            $model = JourneyProjectModel::find($jpid);

            if (!$model) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jpbudget = new JpbudgetModel;
            $jpbudget->jpid = $model->jpid;
            $jpbudget->jpbname = $request->jpbname;
            $jpbudget->jpbamount = $request->jpbamount;

            $jpbudget->save();

            return response()->json(['message' => 'Data created successfully'], 200);


        } else {
            return response()->json(['message' => 'jpbamount & jpbname cannot both be null']);
        }

    }

    public function deleteJpbudget_post(Request $request)
    {
        $jpbid = $request->jpbid;
        $jpbudget = JpbudgetModel::find($jpbid);

        if (!$jpbudget) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $jpbudget->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);

    }

    public function updateJpbudget_post(Request $request)
    {

        if ($request->jpbamount != null || $request->jpbname != null) {
            $jpbid = $request->jpbid;
            $jpbudget = JpbudgetModel::find($jpbid);
            if (!$jpbudget) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jpbudget->jpbname = $request->jpbname;
            $jpbudget->jpbamount = $request->jpbamount;

            $jpbudget->save();
            return response()->json(['message' => 'Data updated successfully'], 200);


        } else {
            $jpbid = $request->jpbid;
            $jpbudget = JpbudgetModel::find($jpbid);
            if (!$jpbudget) {
                return response()->json(['message' => 'Data not found'], 404);
            }
            $jpbudget->delete();
            return response()->json(['message' => 'jpbamount deleted successfully'], 204);
        }
    }

    public function selectJpbudget_post(Request $request)
    {
        $jpbid = $request->jpbid;
        $jpbudget = JpbudgetModel::find($jpbid);
        if (!$jpbudget) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($jpbudget, 200);
    }


    public function addJpimage_post(Request $request)
    {

        if ($request->jpimg != null) {

            $jpid = $request->jpid;
            $model = JourneyProjectModel::find($jpid);

            if (!$model) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $jpimage = new JpimageModel;
            $jpimage->jpid = $model->jpid;
            $jpimage->jpimg = $request->jpimg;


            $jpimage->save();

            return response()->json(['message' => 'Data created successfully'], 200);


        } else {
            return response()->json(['message' => 'jpimg cannot be null']);
        }

    }

    public function deleteJpimage_post(Request $request)
    {
        $jpiid = $request->jpiid;
        $jpimage = JpimageModel::find($jpiid);

        if (!$jpimage) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $jpimage->delete();

        return response()->json(['message' => 'Data deleted successfully'], 204);

    }

    public function selectJpimage_post(Request $request)
    {
        $jpiid = $request->jpiid;
        $jpimage = JpimageModel::find($jpiid);
        if (!$jpimage) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($jpimage, 200);

    }

}
