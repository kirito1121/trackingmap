<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    public function convertFileLv($level)
    {

        $rs = $level->data;
        // su ly data tren map
        $mrs = strstr($rs, "i");
        $mrs1 = strstr($mrs, "[");
        $mrs2 = strstr($mrs1, "]");
        $mapR = trim(str_replace($mrs2, ' ', $mrs1));
        $map = explode('|', substr($mapR, 1, strlen($mapR)));

        // su ly train path json
        $trainjs = strstr($rs, "j");
        $trainjsm = strstr($trainjs, "[");
        $trainjsm2 = strstr($trainjsm, "]");
        $trainjsM = trim(str_replace($trainjsm2, ' ', $trainjsm));
        $trainjsRS = explode(',', substr($trainjsM, 1, strlen($trainjsM)));

        // su ly tram path json
        $tramjs = strstr($rs, "l");
        $tramjsm = strstr($tramjs, "[");
        $tramjsm2 = strstr($tramjsm, "],");
        $tramjsM = trim(str_replace($tramjsm2, ' ', $tramjsm));
        $tramjsRS = explode('|', substr($tramjsM, 1, strlen($tramjsM)));
        foreach ($tramjsRS as $key => $item) {
            $tramjsRS[$key] = explode(',', substr($item, 1, (strlen($item) - 2)));
        }

        $variant = strstr($rs, "p");
        $variant1 = strstr($variant, "[");
        $variant2 = strstr($variant1, "]");
        $variantA = trim(str_replace($variant2, ' ', $variant1));
        $variant = explode('|', substr($variantA, 1, strlen($variantA)));
        // Log::info($tramjsRS);

        // chuyen chuoi thanh mang
        $rs = str_replace('[', '', $rs);
        $rs = str_replace(']', '', $rs);
        $array = explode(',', $rs);
        $data = [];
        // su ly mang dua ra du lieu mong muon
        foreach ($array as $value) {
            $arr = explode(':', $value);
            // return $arr;
            if (count($arr) > 1) {
                if ($arr[0] == 'i') {
                    $arr[1] = $map;
                }
                if ($arr[0] == 'j') {
                    $arr[1] = $trainjsRS;
                }
                if ($arr[0] == 'l') {
                    $arr[1] = $tramjsRS;
                }
                if ($arr[0] == 'p') {
                    $arr[1] = $variant;
                }
                $a = [$arr[0] => $arr[1]];
                $data = array_merge($data, $a);
            }
        }
        // Log::info($data);
        return $data;
    }

    public function viewLevel(Request $request)
    {
        $level = $request->get('level');
        $levelVersion = $request->get('version');
        $appVersion = $request->get('appVersion');
        $levelType = intval($request->get('levelType'));
        $sublevel = intval($request->get('sublevel'));
        return $this->readDataMap($level, $levelType, $levelVersion, $appVersion,$sublevel);
    }

    public function readDataMap($level, $levelType = null, $levelVersion = null, $appVersion = null,$sublevel)
    {
        if (!isset($level)) {
            return [];
        }

        if ($levelType === 0) {
            $type = 'Saga';
        } else if ($levelType === 1) {
            $type = 'Rush';
        }
        else if ($levelType === 2) {
            $type = 'Adventure';
        } else {
            $type = "EventX";
        }

        $arlv = mb_split(',',$level);
        // $appVersion = mb_split(',',$appVersion);
        if(count($arlv) === 1){
            $arlv = [$arlv[0],$arlv[0]];
        }

        $levels = DB::table('levels')
            ->when($level, function ($query) use ($arlv) {
                $query->whereBetween('level', $arlv);
            })
            ->when($levelVersion, function ($query) use ($levelVersion) {
                $query->where('levelVersion', $levelVersion);
            })
            ->when($appVersion, function ($query) use ($appVersion) {
                $query->whereIn('AppVersion', $appVersion);
            })
            ->when($levelType >= 0, function ($query) use ($type) {
                $query->where('levelType', $type);
            })
            ->when($sublevel >= 0, function ($query) use ($sublevel) {
                $query->where('sublevel', $sublevel);
            })
            ->orderBy('sublevel', 'ASC')
            ->orderBy('levelVersion', 'DESC')
            ->get();

        $array = [];

        foreach ($levels as $level) {
            $levelitem = $this->convertFileLv($level);
            $c = [];
            for ($g=0; $g < $levelitem['g']; $g++) {
                for ($h=0; $h < $levelitem['h']; $h++) {
                    array_push($c,$g."_".$h);
                }
            }

            $data = [
                'id' => $level->id,
                'level' => $level->level,
                'sublevel' => $level->sublevel,
                'target' => array_search($levelitem['a'], config('entity.targetType')),
                'mapLevel' => $levelitem['g'] . 'x' . $levelitem['h'],
                'countTarget' => $levelitem['b'],
                'move' => $levelitem['c'],
                'hardLevel' => $levelitem['k'] == 0 ? 'No' : 'Yes',
                'version' => $levelitem['v'],
                'appVersion' => $level->appVersion,
                'score' => $levelitem['d'],
                'gara' => $levelitem['f'] ?? null,
                'mana' => $levelitem['o'] ?? null,
                'c' => $c,
                'i' => $levelitem['i'],
                'j' => $levelitem['j'] ?? null,
                'l' => $levelitem['l'] ?? null,
                'p' => $levelitem['p'] ?? null,
                'g' => $levelitem['g'] ?? null,
                'h' => $levelitem['h'] ?? null,
                'cp' =>array_combine($c,$levelitem['i']),
            ];
            $item = collect($levelitem['i']);

            $map = $item->map(function ($m) {
                $arrB = explode(',', $m);
                $data = [];
                foreach ($arrB as $key => $value) {
                    $arrM = explode('_', $value);
                    $data = array_merge($data, [$arrM]);
                }
                $col = collect($data);
                $data = $col->map(function ($item) {
                    for ($i = 0; $i < count($item); $i++) {
                        if ($i == 0) {
                            $item[$i] = array_search($item[$i], config('entity.entityType'));
                        }
                        if ($item[0]) {
                            if ($item[0] == 'TrafficCone') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.levels'));
                                }
                                if ($i == 2) {

                                    $item[$i] = array_search($item[$i], config('entity.entityColor'));
                                }
                            } else if ($item[0] == 'Locker') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.levels'));
                                }
                            } else if ($item[0] == 'Bollard') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.bollard'));
                                }
                            } else if ($item[0] == 'Tunnel') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.direction'));
                                }
                                if ($i == 2) {
                                    $item[$i] = false;
                                }
                                if ($i == 3) {
                                    $item[$i] = false;
                                }
                            } else if ($item[0] == 'Barrier') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.direction'));
                                }
                                if ($i == 2) {
                                    $item[$i] = false;
                                }
                                if ($i == 3) {
                                    $item[$i] = false;
                                }
                            } else if ($item[0] == 'Container') {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.entityColor'));
                                }
                            } else {
                                if ($i == 1) {
                                    $item[$i] = array_search($item[$i], config('entity.direction'));
                                }
                                if ($i == 2) {
                                    $item[$i] = array_search($item[$i], config('entity.entityColor'));
                                }

                            }
                        } else {
                            $item[$i] = null;
                        }
                    }
                    return implode('', $item);
                });
                return $data;
            });

            $data['obstacle'] = array_count_values(array_diff(Arr::flatten($map->toArray()), [""]));

            array_push($array, $data);
            unset($levelitem);
        }
        return $array;
    }

    public function pushLevel(Request $request)
    {
        try {
            $data = $request->data;
            $level = $request->level;
            $subLevel = $request->sublevel ?? 0;
            $levelVersion = $request->levelVersion;
            $appVersion = $request->appVersion;
            $levelType = intval($request->levelType);

            $type = null;
            if ($levelType == 0) {
                $type = 'Saga';
            } else if ($levelType == 1) {
                $type = 'RewardRush/ChallengeRace';
            } else {
                $type = "EventX";
            }
            $lv = DB::table('levels')
                ->where('level', $level)
                ->where('sublevel', $subLevel)
                ->where('levelVersion', $levelVersion)
                ->where('levelType', $type)->first();
            if (!$lv) {
                DB::table('levels')->insert([
                    [
                        'level' => $level,
                        'sublevel' => $subLevel,
                        'levelVersion' => $levelVersion,
                        'AppVersion' => $appVersion,
                        'data' => $data,
                        'levelType' => $type,
                    ],
                ]);
                Log::info("Luu moi");
                return "Lưu mới";
            } else {
                if (strcmp($lv->data, $data) !== 0) {
                    DB::table('levels')->insert([
                        [
                            'level' => $level,
                            'sublevel' => $subLevel,
                            'levelVersion' => $levelVersion,
                            'AppVersion' => $appVersion,
                            'data' => $data,
                            'levelType' => $type,
                        ],
                    ]);
                    Log::info("Luu moi version");
                    return "Lưu mới version";
                }
            }
            Log::info("Không lưu");
            return "Không lưu";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function pushLevels(Request $request)
    {
        try {
            Log::info($request->data);
            $data = json_decode(trim($request->data));
            $levelType = $request->levelType;
            Log::info($levelType);
            $type = null;
            if ($levelType == 0) {
                $type = 'Saga';
            } else if ($levelType == 1) {
                $type = 'Rush';
            } else if ($levelType == 2) {
                $type = 'Adventure';
            } else {
                $type = "EventX";
            }
            // $lvs = collect(DB::table('levels')->where('levelType', $type)->get());
            $arrayInsert = [];
            foreach ($data as $item) {
                // if (count($lvs) > 0) {
                //     $levels = $lvs->where('level', $item->level);
                //     if (count($levels) > 0) {
                //         if (!in_array($item->data, $levels->pluck('data')->toArray())) {
                //             array_push($arrayInsert, [
                //                 'level' => $item->level,
                //                 'sublevel' => $item->sublevel,
                //                 'levelVersion' => $item->levelVersion,
                //                 'appVersion' => $item->appVersion,
                //                 'data' => $item->data,
                //                 'levelType' => $type,
                //             ]);
                //         }
                //     }
                // } else {
                    array_push($arrayInsert,
                        [
                            'level' => $item->level,
                            'sublevel' => $item->sublevel,
                            'levelVersion' => $item->levelVersion,
                            'appVersion' => $item->appVersion,
                            'data' => $item->data,
                            'levelType' => $type,
                        ]
                    );
                // }
            }
            Log::info("--------------arrayInsert------------------");
            Log::info($arrayInsert);
            if (count($arrayInsert) > 0) {
                DB::table('levels')->insert($arrayInsert);
            }
            return $arrayInsert;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getLevel(Request $request)
    {
        try {
            $level = $request->get('level');
            $sublevel = $request->get('sublevel');
            $levelVersion = $request->get('levelVersion');
            $appVersion = $request->get('appVersion');
            $levelType = intval($request->get('levelType'));
            $type = null;
            if ($levelType == 0) {
                $type = 'Saga';
            } else if ($levelType == 1) {
                $type = 'Rush';
            } else if ($levelType == 2) {
                $type = 'Adventure';
            } else {
                $type = "EventX";
            }
            $data = DB::table('levels')->select('data')
                ->when($level, function ($query) use ($level) {
                    $query->where('level', $level);
                })
                ->when($sublevel, function ($query) use ($sublevel) {
                    $query->where('sublevel', $sublevel);
                })
                ->when($levelVersion, function ($query) use ($levelVersion) {
                    $query->where('levelVersion', $levelVersion);
                })
                ->when($appVersion, function ($query) use ($appVersion) {
                    $query->where('AppVersion', $appVersion);
                })
                ->when($type, function ($query) use ($type) {
                    $query->where('levelType', $type);
                })->orderBy('levelVersion', 'DESC')
                ->first();
            if ($data) {
                return response()->json($data->data);
            }
            return 'Not found';
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getLevels(Request $request)
    {
        try {
            $start = $request->get('startLevel');
            $end = $request->get('endLevel');
            $sublevel = $request->get('sublevel');
            $levelVersion = $request->get('levelVersion');
            $appVersion = $request->get('appVersion');
            $levelType = intval($request->get('levelType'));

            $type = null;
            if ($levelType == 0) {
                $type = 'Saga';
            } else if ($levelType == 1) {
                $type = 'Rush';
            } else if ($levelType == 2) {
                $type = 'Adventure';
            } else {
                $type = "EventX";
            }

            $data = DB::table('levels')->select('data', 'levelVersion', 'appVersion', 'level', 'sublevel')
                ->when($start && $end, function ($query) use ($start, $end) {
                    $query->whereBetween('level', [$start, $end]);
                })
                ->when($sublevel, function ($query) use ($sublevel) {
                    $query->where('sublevel', $sublevel);
                })
                ->when($levelVersion, function ($query) use ($levelVersion) {
                    if ($levelVersion == -1) {
                        $query->orderBy('levelVersion', 'DESC');
                    } else {
                        $query->where('levelVersion', $levelVersion);
                    }
                })
                ->when($appVersion, function ($query) use ($appVersion) {
                    if ($appVersion == -1) {
                        $query->orderBy('levelVersion', 'DESC');
                    } else {
                        $query->where('appVersion', $appVersion);
                    }
                })
                ->when($type, function ($query) use ($type) {
                    $query->where('levelType', $type);
                })
                ->get();
            if ($data) {
                return response()->json($data);
            }
            return 'Not found';
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function delete(Request $request){
        DB::table('levels')->where('id',$request->get('id'))->delete();
        return "done";
    }
}
