<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\UserModel;
class Index extends Controller
{
    public function index()
    {
        // zhangsan 2020-06-18 16:48:28
        // lisi 2020-06-17 16:48:28
        // wangwu   2020-06-16 16:48:28
        // hqwqwq   2020-06-15 16:48:28
        $keyword = input('get.keyword','');
        $startTime = input('get.startTime','');
        $endTime = input('get.endTime','');

        // var_dump($keyword);
        $where = [];
        if(!empty($keyword)){
            $where = [
                'username' => [
                    'like','%'. $keyword.'%'
                ]
            ];
        }
        if(!empty($startTime) && !empty($endTime)){
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            // var_dump($startTime);
            // var_dump($endTime);
            $where = [
                'loginTime' => [
                    ['<=',$endTime],
                    ['>=',$startTime]
                ]
            ];
        }
        $result = db('wjx_user')->where($where)->paginate(2);
        return $this->fetch('index',['list'=>$result]);
        
    }
    public function reg(){
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
            $post = $_POST;
            $user = new UserModel();
            $user->username = $post['username'];
            $user->password = $post['pwd'];
            $user->address = '厦门';

            // $where = [
            //     'username'=>$username,
            //     'password'=>$password,
            //     'address'=>'厦门'
            // ];
            // $result = db('wjx_user')->insert($where, true);

            if($user->save()){
                $resultJson = [
                    'error_code' => 10000,
                    'msg' => '注册成功!'
                ];
            }else{
                $resultJson = [
                    'error_code' => 10002,
                    'msg' => '注册失败!'
                ];
            }

        }
        echo json_encode($resultJson);
    }
    public function login()
    {
        return $this->fetch('login');
       
       
    }
    public function dologin(){
        
        $username = input('post.username','');
        $password = input('post.pwd','');
        $code = input('post.code','');
        // var_dump($username);
        // var_dump($password);
        // var_dump($code);
        
        if(!captcha_check($code)){
            //验证失败
            // echo '验证码错误';
            $resultJson = [
                'error_code' => 10001,
                'msg' => '验证码错误!'
            ];
        }else{
           
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
                Session::set('username', $username);

           }else{
            $resultJson = [
                'error_code' => 10002,
                'msg' => '账号或密码错误!'
            ];
           }
        };
        echo json_encode($resultJson);
    }
    public function addUser(){
        $username = input('post.username','');
        $password = input('post.pwd','');
        $address = input('post.address','');
        // var_dump($username);
        // var_dump($password);
        // var_dump($address);
        $data = [
            'username' => $username,
            'password' => $password,
            'address' => $address,
            'loginTime'=>time()

        ];
        $result = db('wjx_user')->insert($data);
        // var_dump($result);
        if($result){
            $resultJson = [
                'error_code' => 10000,
                'msg' => '添加成功'
            ];
        }else{
            $resultJson = [
                'error_code' => 10001,
                'msg' => '添加失败'
            ];
        }
        echo json_encode($resultJson);
    }
    public function delUser(){
        $userid = input('post.userid','');
        $result = db('wjx_user')->where('id',$userid)->delete();
        if($result){
            $resultJson = [
                'error_code' => 10000,
                'msg' => '删除成功'
            ];
        }else{
            $resultJson = [
                'error_code' => 10001,
                'msg' => '删除失败'
            ];
        }
        echo json_encode($resultJson);
    }
    public function editUser(){
        $username = input('post.username','');
        $password = input('post.pwd','');
        $address = input('post.address','');
        $id = input('post.id','');
        $where = [
            'username'=>$username,
            'password'=>$password,
            'address'=>$address,
        ];
        $result = db('wjx_user')->where('id',$id)->update($where);
        if($result){
            $resultJson = [
                'error_code' => 10000,
                'msg' => '修改成功'
            ];
        }else{
            $resultJson = [
                'error_code' => 10001,
                'msg' => '修改失败'
            ];
        }
        echo json_encode($resultJson);
    }
}
