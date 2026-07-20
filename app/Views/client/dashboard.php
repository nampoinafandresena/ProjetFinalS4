<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .btn-primary { background: #0f766e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #0d6b63; transform: scale(0.98); }
        .btn-ghost { background: transparent; color: #475569; padding: 10px 16px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-ghost:hover { background: #f1f5f9; }
        .action-card { padding: 24px; border-radius: 24px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; cursor: pointer; text-align: left; }
        .action-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.06); }
        .icon-circle { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .icon-depot { background: #ecfdf5; color: #0f766e; }
        .icon-retrait { background: #fffbeb; color: #d97706; }
        .icon-transfert { background: #eff6ff; color: #2563eb; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 50; display: none; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: white; border-radius: 24px; max-width: 420px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 0; animation: scaleIn 0.2s ease-out; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; }
        .input-field { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; }
        .input-field:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
        .alert-success { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border: 1px solid #fecaca; }
        .info-badge { background: #f1f5f9; color: #475569; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .frais-loading { display: inline-block; width: 16px; height: 16px; border: 2px solid #f1f5f9; border-top-color: #0f766e; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v-5m0 0V8m0 5h5m-5 0H7m6-9H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 leading-tight"><?= $user['numero'] ?></p>
                    <p class="text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?= $user['numero'] ?>
                    </p>
                </div>
            </div>
            <a href="<?= base_url('logout') ?>" class="btn-ghost text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
            </a>
        </div>
    </header>

    <!-- Messages Flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-4">
            <div class="alert-success">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-4">
            <div class="alert-error">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">
        <!-- Balance Card -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 p-8 text-white shadow-2xl shadow-slate-900/20">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-teal-500/10 blur-3xl"></div>
            <div class="relative">
                <p class="text-emerald-200/80 text-sm font-medium uppercase tracking-wider">Solde disponible</p>
                <p class="text-5xl font-bold mt-2 tracking-tight"><?= number_format($user['solde'], 0, ',', ' ') ?> <span class="text-xl font-normal text-emerald-200/60">Ar</span></p>
                <div class="flex gap-6 mt-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-200/70">Entrées</p>
                            <p class="font-semibold text-sm"><?= number_format($total_in ?? 0, 0, ',', ' ') ?> Ar</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-rose-200/70">Sorties</p>
                            <p class="font-semibold text-sm"><?= number_format($total_out ?? 0, 0, ',', ' ') ?> Ar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="action-card" onclick="openModal('depot')">
                <div class="icon-circle icon-depot">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <p class="font-semibold text-slate-900">Dépôt</p>
                <p class="text-sm text-slate-500 mt-1">Approvisionner votre compte</p>
                <span class="info-badge mt-2 inline-block">Sans frais</span>
            </div>

            <div class="action-card" onclick="openModal('retrait')">
                <div class="icon-circle icon-retrait">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
                <p class="font-semibold text-slate-900">Retrait</p>
                <p class="text-sm text-slate-500 mt-1">Retirer de l'argent</p>
                <span class="info-badge mt-2 inline-block">Frais selon barème</span>
            </div>

            <div class="action-card" onclick="openModal('transfert')">
                <div class="icon-circle icon-transfert">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <p class="font-semibold text-slate-900">Transfert</p>
                <p class="text-sm text-slate-500 mt-1">Envoyer à un proche</p>
                <span class="info-badge mt-2 inline-block">Frais selon barème</span>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Transactions récentes
                </h2>
                <a href="<?= base_url('client/history') ?>" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Voir tout</a>
            </div>
            <div class="divide-y divide-slate-50">
                <?php if (!empty($recent_transactions)): ?>
                    <?php 
                    // Éliminer les doublons par ID
                    $uniqueTransactions = [];
                    $seenIds = [];
                    foreach ($recent_transactions as $tx) {
                        if (!in_array($tx['id'], $seenIds)) {
                            $seenIds[] = $tx['id'];
                            $uniqueTransactions[] = $tx;
                        }
                    }
                    ?>
                    <?php foreach ($uniqueTransactions as $tx): ?>
                    <?php 
                        $typeLabel = $tx['type_label'] ?? 'Inconnu';
                        $isDepot = ($typeLabel === 'dépôt');
                        $isRetrait = ($typeLabel === 'retrait');
                        $isTransfert = ($typeLabel === 'transfert');
                        
                        $montantDisplay = $tx['montant'];
                        $signe = '+';
                        $couleur = 'text-emerald-600';
                        
                        if ($isDepot) {
                            $signe = '+';
                            $couleur = 'text-emerald-600';
                            $montantDisplay = $tx['montant'];
                        } elseif ($isRetrait) {
                            $signe = '-';
                            $couleur = 'text-rose-600';
                            $montantDisplay = $tx['montant'] + $tx['frais_appliques'];
                        } elseif ($isTransfert) {
                            if ($tx['user1'] == $user['id']) {
                                $signe = '-';
                                $couleur = 'text-rose-600';
                                $montantDisplay = $tx['montant'] + $tx['frais_appliques'];
                            } else {
                                $signe = '+';
                                $couleur = 'text-emerald-600';
                                $montantDisplay = $tx['montant'];
                            }
                        }
                    ?>
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl <?= $isDepot ? 'bg-emerald-50 text-emerald-600' : ($isTransfert ? 'bg-sky-50 text-sky-600' : 'bg-amber-50 text-amber-600') ?> flex items-center justify-center flex-shrink-0">
                            <?php if ($isDepot): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            <?php elseif ($isRetrait): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900">
                                <?php 
                                    if ($isDepot) echo 'Dépôt';
                                    elseif ($isRetrait) echo 'Retrait';
                                    elseif ($isTransfert) echo 'Transfert';
                                    else echo $typeLabel;
                                ?>
                            </p>
                            <p class="text-xs text-slate-500"><?= date('d/m/Y H:i', strtotime($tx['date_transaction'])) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold <?= $couleur ?>">
                                <?= $signe ?> <?= number_format($montantDisplay, 0, ',', ' ') ?> Ar
                            </p>
                            <?php if (isset($tx['frais_appliques']) && $tx['frais_appliques'] > 0): ?>
                                <p class="text-xs text-slate-400">Frais: <?= number_format($tx['frais_appliques'], 0, ',', ' ') ?> Ar</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="px-6 py-8 text-center text-slate-400">
                        <p>Aucune transaction pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal Opération -->
    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-slate-900" id="modalTitle">Dépôt</h3>
                <button onclick="closeModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="operationForm" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="space-y-5">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-xs text-slate-500">Solde actuel</p>
                            <p class="text-2xl font-bold text-slate-900"><?= number_format($user['solde'], 0, ',', ' ') ?> Ar</p>
                        </div>

                        <div id="recipientField" style="display:none;">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro du destinataire</label>
                            <input type="tel" name="destinataire" id="destinataire" placeholder="0331234567" class="input-field" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Montant (Ar)</label>
                            <input type="number" name="montant" id="montantInput" placeholder="0" min="0" step="100" class="input-field text-lg font-bold" oninput="calculerFrais()" />
                        </div>

                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200" id="fraisDisplay">
                            <div class="flex justify-between text-sm">
                                <span class="text-amber-700" id="fraisLabel">Frais estimés :</span>
                                <span class="font-bold text-amber-800" id="fraisValue">
                                    <span class="frais-loading" id="fraisLoading" style="display:none;"></span>
                                    <span id="fraisText">0 Ar</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-all">Annuler</button>
                            <button type="submit" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all">Confirmer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentOperation = 'depot';
        let fraisTimeout = null;
        const depotUrl = '<?= base_url('client/depot') ?>';
        const retraitUrl = '<?= base_url('client/retrait') ?>';
        const transfertUrl = '<?= base_url('client/transfert') ?>';
        const fraisUrl = '<?= base_url('client/calculer-frais') ?>';

        function openModal(type) {
            const modal = document.getElementById('modal');
            const title = document.getElementById('modalTitle');
            const recipient = document.getElementById('recipientField');
            const form = document.getElementById('operationForm');
            const fraisDisplay = document.getElementById('fraisDisplay');

            currentOperation = type;

            if (type === 'depot') {
                title.textContent = 'Dépôt';
                recipient.style.display = 'none';
                form.action = depotUrl;
                fraisDisplay.style.display = 'block';
                document.getElementById('fraisText').textContent = '0 Ar';
            } else if (type === 'retrait') {
                title.textContent = 'Retrait';
                recipient.style.display = 'none';
                form.action = retraitUrl;
                fraisDisplay.style.display = 'block';
            } else {
                title.textContent = 'Transfert';
                recipient.style.display = 'block';
                form.action = transfertUrl;
                fraisDisplay.style.display = 'block';
            }

            modal.classList.add('active');
            document.getElementById('montantInput').value = '';
            document.getElementById('fraisText').textContent = '0 Ar';
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('active');
        }

        function calculerFrais() {
            const montant = document.getElementById('montantInput').value;

            if (!montant || parseInt(montant) <= 0) {
                document.getElementById('fraisText').textContent = '0 Ar';
                document.getElementById('fraisLoading').style.display = 'none';
                return;
            }

            if (currentOperation === 'depot') {
                document.getElementById('fraisText').textContent = '0 Ar';
                document.getElementById('fraisLoading').style.display = 'none';
                return;
            }

            document.getElementById('fraisLoading').style.display = 'inline-block';
            document.getElementById('fraisText').textContent = 'Calcul...';

            if (fraisTimeout) {
                clearTimeout(fraisTimeout);
            }

            fraisTimeout = setTimeout(function() {
                fetch(fraisUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'montant=' + encodeURIComponent(montant)
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('fraisLoading').style.display = 'none';
                    if (data.success) {
                        if (currentOperation === 'retrait') {
                            document.getElementById('fraisText').textContent = data.frais_retrait_formate;
                            document.getElementById('fraisLabel').textContent = 'Frais (Retrait) :';
                        } else {
                            document.getElementById('fraisText').textContent = data.frais_transfert_formate;
                            document.getElementById('fraisLabel').textContent = 'Frais (Transfert) :';
                        }
                    } else {
                        document.getElementById('fraisText').textContent = '0 Ar';
                    }
                })
                .catch(() => {
                    document.getElementById('fraisLoading').style.display = 'none';
                    document.getElementById('fraisText').textContent = 'Erreur';
                });
            }, 300);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
        
        document.getElementById('modal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeModal();
        });
    </script>
</body>
</html>