<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Historique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .btn-ghost { background: transparent; color: #475569; padding: 10px 16px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-ghost:hover { background: #f1f5f9; }
        .filter-btn { padding: 10px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0; background: white; color: #475569; cursor: pointer; transition: all 0.2s; }
        .filter-btn.active { background: #0f172a; color: white; border-color: #0f172a; }
        .filter-btn:hover:not(.active) { background: #f1f5f9; }
        .input-search { width: 100%; padding: 12px 16px 12px 44px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; }
        .input-search:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
        .btn-primary { background: #0f766e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #0d6b63; transform: scale(0.98); }
    </style>
</head>
<body>
    <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('client/dashboard') ?>" class="btn-ghost text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <h1 class="font-semibold text-slate-900 text-lg">Historique</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Solde</span>
                <span class="font-bold text-emerald-600"><?= number_format($user['solde'], 0, ',', ' ') ?> Ar</span>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">
        <!-- Filtres -->
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Rechercher..." class="input-search" onkeyup="filterTransactions()" />
            </div>
            <div class="flex gap-2 overflow-x-auto">
                <button class="filter-btn active" data-filter="all" onclick="setFilter('all', this)">Toutes</button>
                <button class="filter-btn" data-filter="dépôt" onclick="setFilter('dépôt', this)">Dépôts</button>
                <button class="filter-btn" data-filter="retrait" onclick="setFilter('retrait', this)">Retraits</button>
                <button class="filter-btn" data-filter="transfert" onclick="setFilter('transfert', this)">Transferts</button>
            </div>
        </div>

        <!-- Tableau -->
        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
            <div class="divide-y divide-slate-50" id="transactionsList">
                <?php if (!empty($transactions)): ?>
                    <?php 
                    // Éliminer les doublons par ID (sécurité supplémentaire)
                    $uniqueTx = [];
                    $seenIds = [];
                    foreach ($transactions as $tx) {
                        if (!in_array($tx['id'], $seenIds)) {
                            $seenIds[] = $tx['id'];
                            $uniqueTx[] = $tx;
                        }
                    }
                    $transactions = $uniqueTx;
                    ?>
                    
                    <?php foreach ($transactions as $tx): ?>
                    <div class="transaction-item flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition-colors" data-type="<?= $tx['type_label'] ?? '' ?>">
                        <div class="w-10 h-10 rounded-xl <?= $tx['is_depot'] ? 'bg-emerald-50 text-emerald-600' : ($tx['is_transfert'] ? 'bg-sky-50 text-sky-600' : 'bg-amber-50 text-amber-600') ?> flex items-center justify-center flex-shrink-0">
                            <?php if ($tx['is_depot']): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            <?php elseif ($tx['is_retrait']): ?>
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
                                    if ($tx['is_depot']) echo 'Dépôt';
                                    elseif ($tx['is_retrait']) echo 'Retrait';
                                    elseif ($tx['is_transfert']) {
                                        if ($tx['user1'] == $user['id']) {
                                            echo 'Transfert envoyé';
                                            if ($tx['autre_user']) {
                                                echo ' vers ' . $tx['autre_user']['numero'];
                                            }
                                        } else {
                                            echo 'Transfert reçu';
                                            if ($tx['autre_user']) {
                                                echo ' de ' . $tx['autre_user']['numero'];
                                            }
                                        }
                                    }
                                    else echo $tx['type_label'] ?? 'Transaction';
                                ?>
                            </p>
                            <p class="text-xs text-slate-500"><?= date('d/m/Y H:i', strtotime($tx['date_transaction'])) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold <?= $tx['couleur'] ?>">
                                <?= $tx['signe'] ?> <?= number_format($tx['montant_affiche'], 0, ',', ' ') ?> Ar
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

        <!-- Résumé -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-500">Total dépôts</p>
                <p class="text-xl font-bold text-emerald-600">+ <?= number_format($total_depots ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-500">Total retraits</p>
                <p class="text-xl font-bold text-rose-600">- <?= number_format($total_retraits ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-500">Total transferts</p>
                <p class="text-xl font-bold text-sky-600">- <?= number_format($total_transferts ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-500">Total frais</p>
                <p class="text-xl font-bold text-amber-600">+ <?= number_format($total_frais ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </main>

    <script>
        let currentFilter = 'all';

        function setFilter(filter, btn) {
            currentFilter = filter;
            
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            filterTransactions();
        }

        function filterTransactions() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.transaction-item');
            
            items.forEach(item => {
                const type = item.dataset.type || '';
                const text = item.textContent.toLowerCase();
                
                let show = true;
                
                if (currentFilter !== 'all' && type !== currentFilter) {
                    show = false;
                }
                
                if (show && search && !text.includes(search)) {
                    show = false;
                }
                
                item.style.display = show ? 'flex' : 'none';
            });
        }
    </script>
</body>
</html>