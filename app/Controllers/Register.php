<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
 
 
class Register extends BaseController
{
    use ResponseTrait;
 
    public function index(){
        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];
            
  
        if($this->validate($rules)){
            $model = new UserModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'name'    => $this->request->getVar('name'),
                'phone'    => $this->request->getVar('phone'),
                'identificator'    => $this->request->getVar('identificator'),
                'active'    => 1,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $result = $model->save($data);
            $datadb = $model->find($model->insertID);
            if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Registro insertado con exito',
                'data' => ['user'=>$datadb]];
            }else{
             $response = [
                'status'   => 400,
                'error'    => $result,
                'messages' => 'Se ha producido un error']; 
            }
             
             return $this->respond($response,200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response , 409);
             
        }
            
    }
}