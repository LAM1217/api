<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\MenuModel;
$base = new BaseController();

class User extends BaseController
{
    use ResponseTrait;
     
         
    public function index(){
        $this->checktoken();
        $users = new UserModel;
        $data=['users'=>$users->findAll()];
        if($data){
            return $this->respond(['status' => 200,
                                    'message' => 'success',
                                    'data'=>$data]
                                    ,200);
        }else{
            return $this->failNotFound('No Data Found with id ');
        } 
    }

    public function search($id=null){
        $users = new UserModel();
        $menu = new MenuModel();
        $data["users"] = $users->getWhere(['id' => $id])->getResult();
        //$data["users"]['menu'] = $menu->getWhere(['iduser' => $id])->getResult();
        if($data){
            return $this->respond(['status' => 200,
                                    'message' => 'success',
                                    'data'=>$data]
                                    ,200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($id = null){
        $users = new UserModel();
        $input = $this->request->getVar();
        $data = $users->find($id);
        $data = [
            'name' => $input['name'],
            'phone' => $input['phone'],
            'identificator' => $input['identificator'],
            'email' => $input['email'],
            'active'   => 1

        ];
        $result = $users->update($id, $data);
        if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Actualizado con exito',
                'data' => ['user'=>$data]];
        }else{
           $response = [
                'status'   => 400,
                'error'    => $data,
                'messages' => 'Se ha producido un error']; 
        }
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $users = new UserModel();
        $data = $users->find($id);
        if($data){
            $users->delete($id);
            $response = [
                'status'   => 200,
                'messages' =>  'Registro Eliminado',
                'data' => [$data]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function paged($page = null){
        $pager = service('pager');
        $users = new UserModel();
        $data = [
            'users' => $users->paginate(1),
            'pager' => $users->pager
        ];
        return $this->respond($data);
    }

    public function check(){
        $data = "ok 200";
        return $this->respond($data);
    }

 
}