<?php

namespace app\admin\controller;

use think\Config;
use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;

class Goods extends Base
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
        'goods_name|商品名称'=>'require|max:55',
        'goods_price|商品价格'=>'require|float|gt:0',
        'goods_number|商品数量'=>'require|integer|gt:0'
    ];

    private $ruleMsg = [
        'goods_name.max'=>'商品名称不能超过100个字符',
        'goods_price.float'=>'商品价格格式不对',
        'goods_price.gt'=>'商品价格必须大于0',
        'goods_number.integer'=>'商品数量格式不对',
        'goods_number.gt'=>'商品数量必须大于0'
    ];

    public function update(Request $request, $id){
        //接收参数
        $params = input();
        //2、控制器验证
        $res = $this->validate($params, $this->rule, $this->ruleMsg);
        if ($res !== true){
            return $this->error($res);
        }
        //处理数据（修改数据到数据表）
        GoodsModel::update($params, ['id'=>$id], true);
        //返回（跳转页面）
        $this->success('操作成功', 'index');
    }

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
//        $result = GoodsModel::where('id', $id)->delete();
        $result = GoodsModel::destroy($id);
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
