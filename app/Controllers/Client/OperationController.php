<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Models\PrefixesModel;
use App\Models\OperateurModel;
use App\Models\PromotionTransfertModel;

class OperationController extends BaseController
{
    protected $userModel;
    protected $historiqueModel;
    protected $baremeFraisModel;
    protected $typeOperationModel;
    protected $prefixesModel;
    protected $operateurModel;
    protected $promotionTransfertModel;
    

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->prefixesModel = new PrefixesModel();
        $this->operateurModel = new OperateurModel();
        // $this->promotionTransfertModel = new PromotionTransfertModel();
    }

    
    // DÉPÔT
    public function depot()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }

        $user = session()->get('user');
        $montant = $this->request->getPost('montant');

        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $montant = (float)$montant;

        $result = $this->userModel->updateSolde($user['id'], $montant, 'add');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du dépôt');
        }

        $typeDepot = $this->typeOperationModel->where('label', 'dépôt')->first();

        $data = [
            'user1' => $user['id'],
            'user2' => null,
            'type_mvt' => $typeDepot['id'],
            'montant' => $montant,
            'frais_appliques' => 0,
            'commission_appliquee' => 0,
            'montant_total' => $montant,
            'operateur_destinataire' => null,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];

        $this->historiqueModel->insert($data);

        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));

        return redirect()->to('/client/dashboard')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès');
    }

    // RETRAIT (AVEC FRAIS OPTIONNELS)
    public function retrait()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }

        $user = session()->get('user');
        $montant = $this->request->getPost('montant');
        $inclureFrais = $this->request->getPost('inclure_frais') ? true : false;

        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $montant = (float)$montant;
        
        $prefix = substr($user['numero'], 0, 3);
        $operateurData = $this->prefixesModel->where('prefixes', $prefix)->first();
        $operateurId = $operateurData['id_operateur'] ?? null;
        
        $typeRetrait = $this->typeOperationModel->where('label', 'retrait')->first();
        
        $frais = $this->baremeFraisModel->calculerFrais($montant, $typeRetrait['id'], $operateurId);

        // Déterminer le montant total à débiter
        if ($inclureFrais) {
            // Si les frais sont inclus, le client paie le montant + les frais
            $montantTotal = $montant + $frais;
            $montantRetire = $montant; // Le client reçoit le montant demandé
        } else {
            // Si les frais ne sont pas inclus, le client paie seulement le montant
            $montantTotal = $montant;
            $montantRetire = $montant - $frais; // Le client reçoit le montant - les frais
        }

        // Vérifier si le client a assez de solde
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Montant total à débiter : ' . number_format($montantTotal, 0, ',', ' ') . ' Ar');
        }

        // Mettre à jour le solde
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du retrait');
        }

        // Enregistrer l'historique
        $data = [
            'user1' => $user['id'],
            'user2' => null,
            'type_mvt' => $typeRetrait['id'],
            'montant' => $montantRetire, // Le montant réellement reçu par le client
            'frais_appliques' => $frais,
            'commission_appliquee' => 0,
            'montant_total' => $montantTotal,
            'operateur_destinataire' => null,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];

        $this->historiqueModel->insert($data);

        // Mettre à jour la session
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));

        $fraisMessage = $inclureFrais
            ? ' (frais: ' . number_format($frais, 0, ',', ' ') . ' Ar inclus dans le montant total)'
            : ' (frais: ' . number_format($frais, 0, ',', ' ') . ' Ar déduits du montant)';

        return redirect()->to('/client/dashboard')->with(
            'success',
            'Retrait de ' . number_format($montantRetire, 0, ',', ' ') . ' Ar effectué avec succès' . $fraisMessage
        );
    }

    
    // TRANSFERT (AVEC COMMISSION SI AUTRE OPÉRATEUR)
    public function transfert()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }

        $user = session()->get('user');
        $destinataire = $this->request->getPost('destinataire');
        $montant = $this->request->getPost('montant');

        if (empty($destinataire)) {
            return redirect()->back()->with('error', 'Veuillez saisir le numéro du destinataire');
        }

        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $montant = (float)$montant;

        if ($destinataire === $user['numero']) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }

        
        // VÉRIFICATION : LE DESTINATAIRE EXISTE
        
        $destinataireUser = $this->userModel->findByNumero($destinataire);
        if (!$destinataireUser) {
            return redirect()->back()->with('error', 'Le destinataire n\'existe pas');
        }

        if ($destinataireUser['role'] !== 'client') {
            return redirect()->back()->with('error', 'Le destinataire n\'est pas un client valide');
        }
        
        
        // DÉTERMINER L'OPÉRATEUR DE L'ENVOYEUR ET DU DESTINATAIRE
        
        $prefixEnvoyeur = substr($user['numero'], 0, 3);
        $prefixDestinataire = substr($destinataire, 0, 3);
        
        $operateurEnvoyeur = $this->prefixesModel->where('prefixes', $prefixEnvoyeur)->first();
        $operateurDestinataire = $this->prefixesModel->where('prefixes', $prefixDestinataire)->first();
        
        $operateurEnvoyeurId = $operateurEnvoyeur['id_operateur'] ?? null;
        $operateurDestinataireId = $operateurDestinataire['id_operateur'] ?? null;
        
        
        // CALCULER LES FRAIS (TOUJOURS APPLIQUÉS)
        
        $typeTransfert = $this->typeOperationModel->where('label', 'transfert')->first();
        $frais = $this->baremeFraisModel->calculerFrais($montant, $typeTransfert['id'], $operateurEnvoyeurId);

        if ($operateurEnvoyeurId == $operateurDestinataireId ){
            $promotion_transfert = $this->promotionTransfertModel->getPromotionTransfert();
            // si il y a une promotion
            // $frais = $frais * 0.1;
            $frais = $frais * $promotion_transfert;
        }
        
        
        // CALCULER LA COMMISSION (SEULEMENT SI AUTRE OPÉRATEUR)
        
        $commission = 0;
        $commissionPourcentage = 0;
        $operateurDestLabel = null;
        
        if ($operateurEnvoyeurId != $operateurDestinataireId && $operateurDestinataireId) {
            // Récupérer la commission de l'opérateur destinataire
            $operateurDest = $this->operateurModel->find($operateurDestinataireId);
            if ($operateurDest) {
                $commissionPourcentage = (float)$operateurDest['commission'];
                $commission = $montant * ($commissionPourcentage / 100);
                $operateurDestLabel = $operateurDest['operateur'];
            }
        }
        
        
        // MONTANT TOTAL À DÉBITER
        
        $montantTotal = $montant + $frais + $commission;
        
        // Vérifier le solde
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 
                'Solde insuffisant. Montant: ' . number_format($montant, 0, ',', ' ') . 
                ' Ar + Frais: ' . number_format($frais, 0, ',', ' ') . 
                ' Ar + Commission: ' . number_format($commission, 0, ',', ' ') . 
                ' Ar = ' . number_format($montantTotal, 0, ',', ' ') . ' Ar'
            );
        }

        
        // DÉBITER L'ENVOYEUR
        
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du transfert (débit)');
        }

        
        // CRÉDITER LE DESTINATAIRE (SEULEMENT LE MONTANT)
        
        $result = $this->userModel->updateSolde($destinataireUser['id'], $montant, 'add');
        if (!$result) {
            // Annuler le débit
            $this->userModel->updateSolde($user['id'], $montantTotal, 'add');
            return redirect()->back()->with('error', 'Erreur lors du transfert (crédit)');
        }
        
        
        // ENREGISTRER LA TRANSACTION AVEC COMMISSION
        
        $data = [
            'user1' => $user['id'],
            'user2' => $destinataireUser['id'],
            'type_mvt' => $typeTransfert['id'],
            'montant' => $montant,
            'frais_appliques' => $frais,
            'commission_appliquee' => $commission,
            'montant_total' => $montantTotal,
            'operateur_destinataire' => ($commission > 0) ? $operateurDestinataireId : null,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];

        $this->historiqueModel->insert($data);
        
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));
        
        $message = 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $destinataire . ' effectué avec succès';
        if ($frais > 0) {
            $message .= ' (Frais: ' . number_format($frais, 0, ',', ' ') . ' Ar)';
        }
        if ($commission > 0) {
            $message .= ' (Commission ' . $operateurDestLabel . ': ' . number_format($commission, 0, ',', ' ') . ' Ar - ' . $commissionPourcentage . '%)';
        }
        
        return redirect()->to('/client/dashboard')->with('success', $message);
    }

    // API : CALCULER LES FRAIS AVEC OPTION
    public function calculerFrais()
    {
        if (!session()->get('user')) {
            return $this->response->setJSON(['error' => 'Non authentifié'], 401);
        }

        $user = session()->get('user');
        $montant = $this->request->getPost('montant');
        $destinataire = $this->request->getPost('destinataire');
        
        if (empty($montant) || $montant <= 0) {
            return $this->response->setJSON(['error' => 'Montant invalide', 'frais' => 0]);
        }

        $montant = (float)$montant;

        $prefix = substr($user['numero'], 0, 3);
        $operateurData = $this->prefixesModel->where('prefixes', $prefix)->first();
        $operateurId = $operateurData['id_operateur'] ?? null;

        $typeRetrait = $this->typeOperationModel->where('label', 'retrait')->first();
        $typeTransfert = $this->typeOperationModel->where('label', 'transfert')->first();

        $fraisRetrait = $this->baremeFraisModel->calculerFrais($montant, $typeRetrait['id'], $operateurId);
        $fraisTransfert = $this->baremeFraisModel->calculerFrais($montant, $typeTransfert['id'], $operateurId);
        
        
        // CALCUL DE LA COMMISSION POUR LE TRANSFERT
        
        $commissionTransfert = 0;
        $commissionPourcentage = 0;
        $operateurDestLabel = null;
        
        if ($destinataire) {
            $prefixDest = substr($destinataire, 0, 3);
            $operateurDest = $this->prefixesModel->where('prefixes', $prefixDest)->first();
            $operateurDestId = $operateurDest['id_operateur'] ?? null;
            
            if ($operateurDestId && $operateurDestId != $operateurId) {
                $operateur = $this->operateurModel->find($operateurDestId);
                if ($operateur) {
                    $commissionPourcentage = (float)$operateur['commission'];
                    $commissionTransfert = $montant * ($commissionPourcentage / 100);
                    $operateurDestLabel = $operateur['operateur'];
                }
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'montant' => $montant,
            'frais_retrait' => $fraisRetrait,
            'frais_retrait_formate' => number_format($fraisRetrait, 0, ',', ' ') . ' Ar',
            'frais_transfert' => $fraisTransfert,
            'frais_transfert_formate' => number_format($fraisTransfert, 0, ',', ' ') . ' Ar',
            'commission_transfert' => $commissionTransfert,
            'commission_transfert_formate' => number_format($commissionTransfert, 0, ',', ' ') . ' Ar',
            'commission_pourcentage' => $commissionPourcentage,
            'operateur_dest' => $operateurDestLabel,
            'montant_total' => $montant + $fraisTransfert + $commissionTransfert,
            'montant_total_formate' => number_format($montant + $fraisTransfert + $commissionTransfert, 0, ',', ' ') . ' Ar'
        ]);
    }
}
