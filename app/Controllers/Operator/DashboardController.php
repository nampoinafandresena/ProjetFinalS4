<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $historiqueModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueModel();
    }
    
    public function index()
    {
        
        $data['total_clients'] = $this->userModel->where('role', 'client')->countAllResults();
        $data['total_admins'] = $this->userModel->where('role', 'admin')->countAllResults();
        $data['total_users'] = $this->userModel->countAll();
        
    
        $transactions = $this->historiqueModel->findAll();
        $data['total_volume'] = array_sum(array_column($transactions, 'montant'));
        $data['total_frais'] = array_sum(array_column($transactions, 'frais_appliques'));
        
        
        $data['recent_transactions'] = $this->historiqueModel->orderBy('date_transaction', 'DESC')->limit(5)->findAll();
        
        $data['title'] = 'Tableau de bord - Opérateur';
        
        return view('operator/dashboard', $data);
    }
}