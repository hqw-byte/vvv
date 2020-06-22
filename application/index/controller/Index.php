<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index');
    }

    public function login()
    {
        return $this->fetch('login');
    }
    public function reg()
    {
        return $this->fetch('reg');
    }
    public function doReg(){
        $username = input('post.username','');
        $password = input('post.pwd','');
        $result = db('wjx_user')->where('username',$username)->find();
        if($result){
            $resultJson = [
                'error_code' => 10001,
                'msg' => '账号重复，请重新输入!'
            ];
        }else{
           
            $where = [
                'username'=>$username,
                'password'=>$password,
                'address'=>'厦门'
            ];
            $result = db('wjx_user')->insert($where, true);
            if($result){
                $resultJson = [
                    'error_code' => 10000,
                    'msg' => '注册成功!'
                ];
            }

        }
        echo json_encode($resultJson);
    }
    public function dologin(){
        $username = input('post.username','');
        $password = input('post.pwd','');
        $where = [
            'username'=>  $username,
            'password'=>  $password
        ];
        $result = db('wjx_user')->where($where)-> find();
        if($result){
            $resultJson = [
                'error_code' => 10000,
                'msg' => '登录成功!'
            ];
            // Session::set('name','thinkphp');
            Session::set('username', $username);
        }else{
            $resultJson = [
                'error_code' => 10001,
                'msg' => '账号或密码错误，登录失败!'
            ];
           
        }
        echo json_encode($resultJson);

    }
}
