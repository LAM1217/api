<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PlatesModel;

class Plates extends BaseController
{
	use ResponseTrait;
        
    public function index(){
        $plates = new PlatesModel;
        $data= $plates->select('plates.*,menu.name as namemenu,at.name as nameatribute')
                                        ->join('menu as menu', 'menu.idmenu = plates.idmenu', 'left')
                                        ->join('atributes as at', 'at.idatribute = plates.idatribute', 'left')
                                        ->where('menu.iduser',$this->checktoken())
                                        ->findAll();
        return $this->respond(["status"=>200,
                                "data" => ["plates"=>$data]
                                ]);
    }

    public function insert($value=''){
    	$rules = [
            'name' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'description' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'price' => ['rules' => 'required'],
            'idmenu' => ['rules' => 'required'],
            'idatribute' => ['rules' => 'required']
        ];
            
  
        if($this->validate($rules)){
            $plates = new PlatesModel();
            $data = [
                'name'    => $this->request->getVar('name'),
                'description'    => $this->request->getVar('description'),
                'price'    => $this->request->getVar('price'),
                'idmenu'    => $this->request->getVar('idmenu'),
                'idatribute'    => $this->request->getVar('idatribute'),
                'active'    => 1,
                'img'    => $this->request->getVar('img')
            ];
            $plates->save($data);
            return $this->respond(['status'=>200,
                                    'message' => 'Plate Registered Successfully']
                                    , 200);
        }else{
            $response = ['status'=>400,
                        'errors' => $this->validator->getErrors(),
                        'message' => 'Revisar campos'
            ];
            return $this->fail($response , 409);
             
        }
    }

    public function search($id=null){
    	$plates = new PlatesModel();
        $data = $plates->getWhere(['idplate' => $id])->getResult();
        if($data){
            return $this->respond(['plates'=>$data],200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($id = null){
        $plates = new PlatesModel();
        $input = $this->request->getVar();
        $data = [
            'name' => $input['name'],
            'description' => $input['description'],
            'idmenu' => $input['idmenu'],
            'atribute' => $input['atribute'],
            'price' => $input['price'],
            'active' => $input['active'],
            'idmenu' => $input['idmenu']
        ];
        $plates->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $plates = new PlatesModel();
        $data = $plates->find($id);
        if($data){
            $plates->delete($id);
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

    public function paged(){
    	$pager = service('pager');
    	$plates = new PlatesModel();
		$data = [
            'plates' => $plates->paginate(1),
            'pager' => $plates->pager
        ];
		return $this->respond($data);
    }
 
}
