<?php


namespace app\admin\controller;


use app\model\Cate;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\facade\View;

class Cates extends Base
{
    public function index()
    {
        $cates = Cate::select();
        View::assign([
            'cates' => $cates,
            'count' => count($cates)
        ]);
        return view();
    }

    public function create()
    {
        if (request()->isPost()) {
            $aname = trim(input('cate_name'));
            $gender = input('gender');
            try {
                $cate = Cate::where('cate_name', '=', $aname)->findOrFail();
                return json(['err' => 1, 'msg' => '已经存在相同题材']);
            } catch (DataNotFoundException $e) {

            } catch (ModelNotFoundException $e) {
                $cate = new Cate();
                $cate->cate_name = $aname;
                $cate->gender = $gender;
                $result = $cate->save();
                if ($result) {
                    return json(['err' =>0,'msg'=>'添加成功']);
                }else{
                    return json(['err' =>1,'msg'=>'添加失败']);
                }
            }
        }
        return view();
    }

    public function edit()
    {
        $id = input('id');
        try {
            $cate = Cate::findOrFail($id);
            if (request()->isPost()) {
                $cate->cate_name = trim(input('cate_name'));
                $cate->gender = input('gender');
                $result = $cate->save();
                if ($result) {
                    return json(['err' =>0,'msg'=>'修改成功']);
                }else{
                    return json(['err' =>1,'msg'=>'修改失败']);
                }
            }
            View::assign([
                'cate' => $cate,
            ]);
            return view();
        } catch (DataNotFoundException $e) {
            abort(404, $e->getMessage());
        } catch (ModelNotFoundException $e) {
            abort(404, $e->getMessage());
        }



    }

    public function delete()
    {
        $id = input('id');
        $result = Cate::destroy($id);
        if ($result) {
            return json(['err' => '0','msg' => '删除成功']);
        } else {
            return json(['err' => '1','msg' => '删除失败']);
        }
    }

    public function deleteAll($ids){
        $ids = input('ids');
        $result = Cate::destroy($ids);
        if ($result) {
            return json(['err' => '0','msg' => '删除成功']);
        } else {
            return json(['err' => '1','msg' => '删除失败']);
        }
    }
}