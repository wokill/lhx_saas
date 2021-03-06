<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\BusinessController;
use App\Http\Requests\Business\BrandRequest;
use App\Models\Common\BrandModel;
use App\Models\Common\ClassificationModel;
use Illuminate\Http\Request;

class BrandController extends BusinessController
{

    /**
     * 品牌列表
     */
    public function index(){

       return view('business.brand.index');
    }

    /**
     * 添加品牌
     */
    public function create(Request $request){
       return view('business.brand.add');
    }


    /**
     * 添加
     */
    public function store(BrandRequest $request){
        $data= $request->except(['_token','file','id']);
        $data['class_ids']=join(',',$data['class_ids']);
        $data['business_id']=getBusinessId();
        $data['brand_en_name'] =strtoupper($data['brand_en_name']);
        $re = BrandModel::create($data);
        if($re->exists) {
            return $this->success('提交数据成功');
        }else{
            return $this->error('提交数据失败');
        }
    }


    /**
     * 获取数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getdata(Request $request){

        $pageSize = $request->input('length', 10);
        $start = $request->input('start', 0);
        $model = $this->_condition();
        $count = $model->count();
        $result=$model->offset($start)->limit($pageSize)->orderBy('id','desc')->get();
        $data = [
            'draw' => intval($request->input['draw']),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => []
        ];
        foreach ($result as  $val){

            if($val['status'] == 1){
                $btn_status =[false,"disableStatus(2,{$val->id})"];
            }else{
                $btn_status =[true,"disableStatus(1,{$val->id})"];
            }
            $action =['getBusinessEditActionButton'=>'','getBusinessDestroyAjaxButton'=>"deleted({$val->id})",'getBusinessChangeActionButton'=>$btn_status];
            $btn = $val->getBusinessActionButton($action);
            $class='';
            if($val->class_ids){
                if($mo = ClassificationModel::find(explode(',',$val->class_ids))){
                    $brands = array_column($mo->toArray(),'name');
                    $class = join(',',$brands);
                }
            }
            $data['data'][]=[
                'ck'=>'<input type="checkbox" name="checkbox" value="'.$val->id.'" />',
                'id'=>$val->id,
                'brand_name'=>$val->brand_name,
                'logo'=>'<img src="'.$val->logo.'" alt="'.$val->name.'" height="60" />',
                'brand_en_name'=>$val->brand_en_name,
                'content'=>$val->content,
                'status'=>BrandModel::getStatusHtml($val->status),
                'class_ids'=>$class,
                'site'=>$val->site,
                'action'=>$btn
            ];
        }
        return response()->json($data);
    }

    /**
     * 编辑界面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,$id){
        $result=BrandModel::find($id);
        if(!$result) {
            abort(403,'当前品牌不存在');
        }
        $class=ClassificationModel::where('status',1)->get();
        return view('business.brand.edit',['info'=>$result,'class'=>$class]);
    }


    /**
     * 更新操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BrandRequest $request){

        if(!$request->isMethod('post') || !$request->ajax()){
            abort(404,'访问错误.');
        }
        $data= $request->except(['_token','file','id']);
        $data['class_ids']=join(',',$data['class_ids']);
        $data['brand_en_name'] =strtoupper($data['brand_en_name']);
        if( BrandModel::where('id',$request->id)->update($data)){
            return $this->success('更新成功');
        }else{
            return $this->error('操作失败');
        }
    }

    /**
     * 更改状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(Request $request)
    {
        if(!$request->isMethod('post') || !$request->ajax()){
            abort(404,'访问错误.');
        }
        if( BrandModel::where('id',$request->id)->update(['status'=>$request->status])){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }

    /**
     * 软删除数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request){
        if(!$request->isMethod('post') || !$request->ajax()){
            abort(404,'访问错误.');
        }
        if( BrandModel::where('id',$request->id)->delete()){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    /**
     * 查询条件
     * @param Request $request
     * @param array $tj
     * @return mixed
     */
    private  function _condition(Array $tj=[]){
        $post=request()->all();
        // datatables是否启用模糊搜索
        $regex = request('search.regex', false);
        // 搜索框中的值
        $value = request('search.value', '');
        $where=[['business_id',getBusinessId()]];
        $tj && array_push($where,$tj);
        if(!empty($post['status']) && in_array($post['status'],[BrandModel::_STATUS_START,BrandModel::_STATUS_STOP,BrandModel::_STATUS_OUT])){
            $where[]=['status',(int)$post['status']];
        }
        $model=BrandModel::where($where);
        if($value){
            if($regex){
                $model->where(function($query) use($value){
                    $query->where('brand_name','like','%'.$value.'%')
                        ->orWhere('brand_en_name','like','%'.$value.'%')
                        ->orWhere('site','like','%'.$value.'%');
                });
            }else{
                $model->where('brand_name','like','%'.$value.'%');
            }
        }
        return $model;
    }
}
