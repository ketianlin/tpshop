<?php

namespace app\admin\controller;

use think\Config;
use think\Collection;
use app\admin\model\Goods as GoodsModel;
use think\Image;
use think\Request;

class Goods extends Base
{
    public function index(){
        // 接收keyword参数
        $keyword = input('keyword');
        $condition = [];
        if ( ! empty($keyword)){
            $condition['goods_name'] = ['like', "%$keyword%"];
        }
        $data = GoodsModel::where($condition)->order('id desc')->paginate(5, false, [
            'query'=>['keyword'=>$keyword]
        ]);
        return view(null, ['list'=>$data]);
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
        'goods_name|商品名称'=>'require|token|max:55',
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
        //商品logo图片的修改
        $file = request()->file('logo');

        if (empty($file)){
            unset($params['goods_logo']);
        }else{
            $params['goods_logo'] = $this->upload_logo();
            $goods_logo = GoodsModel::where('id',$id)->value('goods_logo');
        }
        //处理数据（修改数据到数据表）
        GoodsModel::update($params, ['id'=>$id], true);
        //删除原图片
        if (isset($goods_logo) && $goods_logo){
            if (file_exists('.'.$goods_logo)){
                unlink('.'.$goods_logo);
            }
        }
        //返回（跳转页面）
        $this->success('操作成功', 'admin/goods/index');
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
        //文件上传
        $data['goods_logo'] = $this->upload_logo();
        //添加数据到数据表  第二个参数true表示过滤非数据表字段
        GoodsModel::create($data, true);
        return $this->success('添加成功', 'admin/goods/index');
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

    private function upload_logo($name = 'logo')
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);

        //判断 是否上传了文件
        if (empty($file)){
            return $this->error('没有上传文件');
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $fileValidate = ['size' => 100*1024*1024, 'ext' => 'jpg,png,gif,jpeg'];
        $info = $file->validate($fileValidate)->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info){
            //上传成功  拼接图片的访问路径  /uploads/20190709/fssdsahfdskasa.jpg
            $goods_logo = DS.'uploads'.DS.$info->getSaveName();
            //生成缩略图  \think\Image类  保存
            //打开图片
            $image = Image::open('.'.$goods_logo);
            // 生成缩略图  保存图片
            $image->thumb(300, 250)->save('.'.$goods_logo);
            return $goods_logo;
        }
        // 上传失败获取错误信息
        $error_msg = $file->getError();
        return $this->error($error_msg);
    }
}
