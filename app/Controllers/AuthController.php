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
        // CodeIgniter démarre déjà la session automatiquement via le service Session.
        // (l'ancien code appelait session()->isStarted, une propriété qui n'existe pas)
    }
 
    public function index()
    {
        if (session()->get('user')) {
            return $this->redirectByRole(session()->get('user')['role']);
        }
        return view('auth/login');
    }
  
    
    // LOGIN CLIENT
    
    
    public function clientLoginForm()
    {
        // Si déjà connecté, rediriger vers le dashboard client
        if (session()->get('user')) {
            $user = session()->get('user');
            if ($user['role'] === 'client') {
                return redirect()->to('/client/dashboard');
            }
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
        }
        
        return view('client/login');
    }
    
    public function clientLogin()
    {
        $numero = trim((string) $this->request->getPost('numero'));

        if (empty($numero)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro valide');
        }

        // Notre opérateur (Telma) et ses préfixes tels que configurés par l'admin
        // (admin/prefixe) -- avant, la liste était codée en dur ['034','038'] et ne
        // tenait donc jamais compte des préfixes ajoutés/désactivés depuis l'admin.
        $operateurModel = new \App\Models\OperateurModel();
        $notreOperateur = $operateurModel->findByOperateur('Telma');

        $prefixesValides = $notreOperateur
            ? array_column($this->prefixesModel->getPrefixesByOperateur($notreOperateur['id']), 'prefixes')
            : [];

        $prefix = substr($numero, 0, 3);

        if (empty($prefixesValides) || !in_array($prefix, $prefixesValides, true)) {
            $listeAffichee = !empty($prefixesValides) ? implode('/', $prefixesValides) : 'aucun';
            return redirect()->back()->with('error', 'Seuls les numéros Telma (' . $listeAffichee . ') sont acceptés');
        }

        $prefixActif = $this->prefixesModel->where('prefixes', $prefix)->where('actif', 1)->first();
        if (!$prefixActif) {
            return redirect()->back()->with('error', 'Le préfixe ' . $prefix . ' est désactivé.');
        }

        $user = $this->userModel->findByNumero($numero);

        if (!$user) {
            // Énoncé : "Login automatique avec le numéro de téléphone, pas d'inscription
            // au préalable" -> on crée le compte client à la volée avec un solde de 0.
            $newId = $this->userModel->insert([
                'numero' => $numero,
                'solde'  => 0,
                'role'   => 'client',
            ], true);

            if (!$newId) {
                $errors = $this->userModel->errors();
                return redirect()->back()->withInput()->with('error', $errors ? implode(' ', $errors) : 'Numéro invalide.');
            }

            $user = $this->userModel->find($newId);
        }

        if ($user['role'] !== 'client') {
            return redirect()->back()->with('error', 'Accès non autorisé. Veuillez utiliser l\'espace opérateur.');
        }

        // Créer la session
        session()->set('user', [
            'id'     => $user['id'],
            'numero' => $user['numero'],
            'role'   => $user['role'],
            'solde'  => $user['solde'] ?? 0,
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Bienvenue ' . $user['numero']);
    }

    
    // LOGIN ADMIN / OPÉRATEUR
    
    
    public function adminLoginForm()
    {
        // Si déjà connecté, rediriger vers le dashboard admin
        if (session()->get('user')) {
            $user = session()->get('user');
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            if ($user['role'] === 'client') {
                return redirect()->to('/client/dashboard');
            }
        }
        
        return view('auth/admin-login');
    }
    
    public function adminLogin()
    {
        $numero = $this->request->getPost('numero');
        
        if (empty($numero)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro valide');
        }
        
        $user = $this->userModel->findByNumero($numero);
        if (!$user) {
            return redirect()->back()->with('error', 'Ce numéro n\'existe pas.');
        }
        
        if ($user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Accès non autorisé. Veuillez utiliser l\'espace client.');
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
        
        return redirect()->to('/admin/dashboard')->with('success', 'Bienvenue ' . $user['numero']);
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