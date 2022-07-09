<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ClientModel;
$base = new BaseController();

class Client extends BaseController
{
    public function index(){
        $client = new ClientModel();
        return $this->respond(['clients' => $client->where('iduser',$this->checktoken())->findAll()], 200);
    }

    public function insert($value=''){
        $rules = [
            'name' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'identificator' => ['rules' => 'required|min_length[4]|max_length[255]']];
            
        if($this->validate($rules)){
            $client = new ClientModel();
            $data = [
                'name'    => $this->request->getVar('name'),
                'identificator'    => $this->request->getVar('identificator'),
                'phone'    => $this->request->getVar('phone'),
                'email'    => $this->request->getVar('email'),
                'address'    => $this->request->getVar('address'),
                'type'    => $this->request->getVar('type'),
                'iduser'    => $this->checktoken()
            ];
            $result = $client->save($data);
            $datadb = $client->find($client->insertID);
            if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Registro insertado con exito',
                'data' => ['client'=>$datadb]];
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
        $client = new ClientModel();
        $data = $client->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond(['client'=>$data],200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($id = null){
        //$input = $this->request->getVar();
        $input = $this->request->getJSON();
        if($input){
            $data = [
                'name' => $input->name,
                'phone' => $input->phone,
                'identificator' => $input->identificator,
                'email' => $input->email,
                'address' => $input->address,
                'type' => $input->type     
            ];
        }
        $client = new ClientModel();
        $find = $client->find($id);
        if(!$find){
            $response = [
                'status'   => 400,
                'messages' => 'El cliente no existe',
                'data' => ['client'=> null]];
            return $this->respond($response);
        }
        
        $result = $client->update($id, $data);
        if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Actualizado con exito',
                'data' => ['client'=>$data]];
        }else{
           $response = [
                'status'   => 400,
                'error'    => $data,
                'messages' => 'Se ha producido un error']; 
        }
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $client = new ClientModel();
        $data = $client->find($id);
        if($data){
            $client->delete($id);
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
        $client = new ClientModel();
        $data = [
            'clients' => $client->where('iduser',$this->checktoken())->paginate(1),
            'pager' => $client->pager
        ];
        return $this->respond($data);
    }
 
}
