<?php

namespace app\admin\controller;

use think\Collection;
use think\Config;
use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;

class Goods extends Controller
{
    public function index(){
        $data = GoodsModel::select();
        $list = (new Collection($data))->toArray();
        return view(null, ['list'=>$list]);
    }
    public function test(){
//        $goods = new GoodsModel();
//        $data = $goods->select();
        $data = GoodsModel::where([
            'id'=>['<=',35],
            'goods_price'=>['>',35]
        ])->limit(1)->order('id desc')->select();
        dump($data);
        $data = (new Collection($data))->toArray();
        dump($data);exit;
//        return view();
    }

    public function add(){
        return view();
    }

    private $rule = [
        'goods_name|商品名称'=>'require',
        'goods_price|商品价格'=>'require|float|gt:0',
        'goods_number|商品数量'=>'require|integer|gt:0'
    ];

    private $ruleMsg = [
        'goods_price.float'=>'商品价格格式不对',
        'goods_price.gt'=>'商品价格必须大于0',
        'goods_number.integer'=>'商品数量格式不对',
        'goods_number.gt'=>'商品数量必须大于0'
    ];

    public function edit(){
        $id = input('param.id/d');
        if (empty($id)){
            return $this->error(Config::get('msg.sys_err_msg'));
        }
        $goods = GoodsModel::find($id);
        if (empty($goods)){
            return $this->error(Config::get('msg.sys_err_msg'));
        }
        return view(null, ['goods'=>$goods]);
    }

    public function save(Request $request){
        $data = $request->post();
        $result = $this->validate($data, $this->rule, $this->ruleMsg);
        if ($result !== true){
            return $this->error($result);
        }
        GoodsModel::create($data, true);
        return $this->success('添加成功', 'index');
    }

    public function delete(){
        $id = input('param.id/d');
        if (empty($id)){
            return $this->error(Config::get('msg.sys_err_msg'));
        }
        $result = GoodsModel::where('id', $id)->delete();
        if ($result){
            return $this->success('删除成功');
        }
        return $this->success('删除失败，请稍后再试');
    }

    public function read($id){
        if (empty($id)){
            return $this->error(Config::get('msg.sys_err_msg'));
        }
        $goods = GoodsModel::find($id);
        if (empty($goods)){
            return $this->error(Config::get('msg.sys_err_msg'));
        }
        return view(null, ['goods'=>$goods]);
    }
}
