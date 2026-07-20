<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PrefixesModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $prefixesModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->prefixesModel = new PrefixesModel();
        
        if (!session()->isStarted) {
            session()->start();
        }
    }
 
    public function index()
    {
        if (session()->get('user')) {
            return $this->redirectByRole(session()->get('user')['role']);
        }
        return view('auth/login');
    }
  
    public function clientLoginForm()
    {
        if (session()->get('user')) {
            return $this->redirectByRole(session()->get('user')['role']);
        }
        
        return view('client/login');
    }
    
    public function clientLogin()
    {
        $numero = $this->request->getPost('numero');
        
        if (empty($numero)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro valide');
        }

        // ============================================
        // VÉRIFICATION : LE NUMÉRO DOIT ÊTRE TELMA (034 ou 038)
        // ============================================
        $prefixesTelma = ['034', '038'];
        $prefix = substr($numero, 0, 3);
        
        if (!in_array($prefix, $prefixesTelma)) {
            return redirect()->back()->with('error', 'Seuls les numéros Telma (034/038) sont acceptés');
        }
        
        // Vérifier que le préfixe existe dans la table prefixes
        $prefixExists = $this->prefixesModel->prefixExists($prefix);
        if (!$prefixExists) {
            return redirect()->back()->with('error', 'Le préfixe ' . $prefix . ' n\'est pas configuré.');
        }
        
        $user = $this->userModel->findByNumero($numero);
        if (!$user) {
            return redirect()->back()->with('error', 'Ce numéro n\'existe pas. Veuillez contacter votre opérateur.');
        }
        
        if ($user['role'] !== 'client') {
            return redirect()->back()->with('error', 'Accès non autorisé. Veuillez utiliser l\'espace opérateur.');
        }
       
        session()->set('user', [
            'id'     => $user['id'],
            'numero' => $user['numero'],
            'role'   => $user['role'],
            'solde'  => $user['solde'] ?? 0,
        ]);
        
        if (!session()->get('user')) {
            return redirect()->back()->with('error', 'Erreur de session. Veuillez réessayer.');
        }
        
        return redirect()->to('/client/dashboard')->with('success', 'Bienvenue ' . $user['numero']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Vous êtes déconnecté');
    }
  
    private function redirectByRole($role)
    {
        $urls = [
            'admin'  => '/admin/dashboard',
            'client' => '/client/dashboard',
        ];
        return redirect()->to($urls[$role] ?? '/client/dashboard');
    }
}