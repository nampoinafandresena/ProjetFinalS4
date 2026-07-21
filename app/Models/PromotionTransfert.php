<?php 

namespace App\Models;
use CodeIgniter\Model;

class PromotionTransfert extends Model{
    protected $table = "promotion_transfert";
    protected $primaryKey = "id";
    protected $allowedFields = ["promotion"];

    protected $returnType = 'array';
    protected $useTimestamps = false;


    public function getAllPromotionTransfert() {
        return $this->findAll();
    }

    public function getPromotionTransfert(){
        $prom = $this->find(1);
        return $prom['promotion'] ?? 0;
    }
}