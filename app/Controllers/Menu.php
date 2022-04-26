<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MenuModel;
$base = new BaseController();

class Menu extends BaseController
{
	use ResponseTrait;
        
    public function index(){
        $menu = new MenuModel;
        return $this->respond(['menu' => $menu->where('iduser',$this->checktoken())->findAll()], 200);
    }

    public function insert($value=''){
    	$rules = [
            'name' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'description' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'description_for_plate' => ['rules' => 'required|min_length[4]|max_length[255]']
        ];
            
  
        if($this->validate($rules)){
            $menu = new MenuModel();
            $data = [
                'name'    => $this->request->getVar('name'),
                'description'    => $this->request->getVar('description'),
                'description_for_plate'    => $this->request->getVar('description_for_plate'),
                'iduser'    => $this->checktoken(),
                'icon'    => $this->request->getVar('icon'),
                'active'    => 1
            ];
            $result = $menu->save($data);
            $datadb = $menu->find($menu->insertID);
            if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Registro insertado con exito',
                'data' => ['menu'=>$datadb]];
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
    	$menu = new MenuModel();
        $data = $menu->getWhere(['idmenu' => $id])->getResult();
        if($data){
            return $this->respond(['menu'=>$data],200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($id = null){
        $menu = new MenuModel();
        $input = $this->request->getVar();
        $data = [
            'name' => $input['name'],
            'description' => $input['description'],
            'description_for_plate' => $input['description_for_plate']
        ];
        $result = $menu->update($id, $data);
        $datadb = $menu->find($id);
        if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Data Updated',
                'data' => ['menu' => $datadb]];
        }else{
            $response = [
                'status'   => 400,
                'error' => $data,
                'messages' => 'Se ha producido un error',
                'data' => ['menu' => $datadb]];
        }
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $menu = new MenuModel();
        $data = $menu->find($id);
        if($data){
            $menu->delete($id);
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
    	$menu = new MenuModel();
		$data = [
            'menu' => $menu->where('iduser',$this->checktoken())->paginate(1),
            'pager' => $menu->pager
        ];
		return $this->respond($data);
    }
 
}
