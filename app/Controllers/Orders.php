<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ModelDetailorders;
use App\Models\ModelOrders;
$base = new BaseController();

class Orders extends BaseController
{
    public function index(){
        $order = new ModelOrders();
        //meter codecomerce si es admin, sino meter iduser
        return $this->respond(['orders' => $order->where('iduser',$this->checktoken())->findAll()], 200);
    }

    public function insert($value=''){
        $rules = [
            'total' => ['rules' => 'required|min_length[4]|max_length[255]']];
            
        if($this->validate($rules)){
            $order = new ModelOrders();
            $data = [
                'total'    => $this->request->getVar('total'),
                'idcliente'    => $this->request->getVar('idcliente'),
                'codecomerce'    => $this->request->getVar('codecomerce'),
                'status'    => 'Pendiente',
                'iduser'    => $this->checktoken()
            ];
            $result = $order->save($data);
            $datadb = $order->find($order->insertID);
            if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Orden procesada',
                'data' => ['order'=>$datadb]];
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
        $order = new ModelOrders();
        $data = $order->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond(['order'=>$data],200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    public function update($idrdersModel();
        $find = $order->find($id);
        if(!$find){
            $response = [
                'status'   => 400,
                'messages' => 'La order no existe',
                'data' => ['order'=> null]];
            return $this->respond($response);
        }
        
        $result = $order->update($id, $data);
        if($result){
            $response = [
                'status'   => 200,
                'messages' => 'Actualizado con exito',
                'data' => ['order'=>$data]];
        }else{
           $response = [
                'status'   => 400,
                'error'    => $data,
                'messages' => 'Se ha producido un error']; 
        }
        return $this->respond($response);
    }

    public function delete($id = null){
    
        $order = new ModelOrders();
        $data = $order->find($id);
        if($data){
            $order->delete($id);
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
        $order = new ModelOrders();
        $data = [
            'orders' => $order->where('iduser',$this->checktoken())->paginate(1),
            'pager' => $order->pager
        ];
        return $this->respond($data);
    }
 
}
