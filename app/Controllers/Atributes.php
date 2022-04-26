<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AtributesModel;
$base = new BaseController();

class Atributes extends BaseController
{
    use ResponseTrait;
        
    public function index(){
        $atributes = new AtributesModel;
        return $this->respond(['atributes' => $atributes->where('iduser',$this->checktoken())->findAll()], 200);
    }

    public function insert($value=''){
        $rules = [
            'name' => ['rules' => 'required|min_length[4]|max_length[255]']];
            
        if($this->validate($rules)){
            $atributes = new AtributesModel();
            $data = [
                'name'    => $this->request->getVar('name'),
                'iduser'    => $this->checktoken()
            ];
            $result = $atributes->save($data);
            $datadb = $atributes->find($atributes->insertID);
            if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Registro insertado con exito',
                'data' => ['atributes'=>$datadb]];
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

    public function search($id=null){
        $atributes = new AtributesModel();
        $data = $atributes->getWhere(['idatribute' => $id])->getResult();
        if($data){
            return $this->respond(['atributes'=>$data],200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($id = null){
        $atributes = new AtributesModel();
        $input = $this->request->getVar();
        $data = [
            'name' => $input['name']
        ];
        $result = $atributes->update($id, $data);
        $datadb = $atributes->find($id);
        if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Data Updated',
                'data' => ['atributes' => $datadb]];
        }else{
            $response = [
                'status'   => 400,
                'error' => $data,
                'messages' => 'Se ha producido un error',
                'data' => ['atributes' => $datadb]];
        }
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $atributes = new AtributesModel();
        $data = $atributes->find($id);
        if($data){
            $atributes->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function paged($page = null){
        $pager = service('pager');
        $atributes = new AtributesModel();
        $data = [
            'atributes' => $atributes->where('iduser',$this->checktoken())->paginate(1),
            'pager' => $atributes->pager
        ];
        return $this->respond($data);
    }
 
}
