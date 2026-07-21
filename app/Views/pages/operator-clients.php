<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <title><?= $title ?? 'Mobile Money - Clients' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 256px; background: white; border-right: 1px solid #f1f5f9; padding: 20px; display: none; flex-direction: column; z-index: 40; }
        @media (min-width: 1024px) { .sidebar { display: flex; } }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 12px; font-size: 14px; font-weight: 500; color: #64748b; transition: all 0.2s; width: 100%; border: none; background: transparent; cursor: pointer; }
        .nav-item:hover { background: #f1f5f9; }
        .nav-item.active { background: #0f172a; color: white; }
        .mobile-nav { display: flex; gap: 4px; padding: 8px 16px; background: white; border-bottom: 1px solid #f1f5f9; overflow-x: auto; }
        @media (min-width: 1024px) { .mobile-nav { display: none; } }
        .mobile-nav-item { padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap; border: none; background: #f1f5f9; color: #64748b; cursor: pointer; }
        .mobile-nav-item.active { background: #0f172a; color: white; }
        .client-item { padding: 16px 24px; border-bottom: 1px solid #f8fafc; display: flex; align-items: center; gap: 16px; transition: background 0.2s; }
        .client-item:hover { background: #f8fafc; }
        .input-search { width: 100%; padding: 12px 16px 12px 44px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; }
        .input-search:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="flex items-center gap-3 px-2 py-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div><p class="font-bold text-slate-900 leading-tight">Opérateur</p><p class="text-xs text-slate-500">Mobile Money</p></div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-item">Dashboard</a>
            <a href="<?= base_url('admin/prefixe') ?>" class="nav-item">Préfixes</a>
            <a href="<?= base_url('admin/bareme-frais') ?>" class="nav-item">Opérations & Frais</a>
            <a href="<?= base_url('operator/gains') ?>" class="nav-item">Gains</a>
            <a href="<?= base_url('operator/clients') ?>" class="nav-item active">Clients</a>
        </nav>
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quitter
        </a>
    </aside>

    <!-- Mobile Header & Nav -->
    <header class="lg:hidden bg-white border-b border-slate-100 px-4 py-3 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <span class="font-bold text-slate-900">Opérateur</span>
        </div>
        <a href="<?= base_url('logout') ?>" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </header>
    <nav class="lg:hidden mobile-nav">
        <a href="<?= base_url('admin/dashboard') ?>" class="mobile-nav-item">Dashboard</a>
        <a href="<?= base_url('admin/prefixe') ?>" class="mobile-nav-item">Préfixes</a>
        <a href="<?= base_url('admin/bareme-frais') ?>" class="mobile-nav-item">Frais</a>
        <a href="<?= base_url('operator/gains') ?>" class="mobile-nav-item">Gains</a>
        <a href="<?= base_url('operator/clients') ?>" class="mobile-nav-item active">Clients</a>
    </nav>

    <!-- Main Content -->
    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <!-- En-tête avec statistiques -->
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Clients</h1>
                <p class="text-slate-500 mt-1">
                    <?= $total_clients ?? 0 ?> compte(s) - 
                    <?= number_format($total_fonds ?? 0, 0, ',', ' ') ?> Ar de fonds déposés
                </p>
            </div>

            <!-- Barre de recherche -->
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Rechercher par numéro de téléphone..." class="input-search" onkeyup="filterClients()" />
            </div>

            <!-- Liste des clients -->
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden" id="clientsList">
                <?php if (!empty($clients) && is_array($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                        <div class="client-item" data-numero="<?= esc($client['numero']) ?>">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center font-bold flex-shrink-0">
                                <?= esc($client['initials'] ?? substr($client['numero'], -2)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900">
                                    Client <?= esc($client['numero']) ?>
                                </p>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <?= esc($client['numero']) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900 flex items-center gap-1 justify-end">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <?= number_format($client['solde'] ?? 0, 0, ',', ' ') ?> Ar
                                </p>
                                <p class="text-xs text-slate-400 flex items-center gap-1 justify-end mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <?= $client['last_transaction'] ? date('d/m/Y', strtotime($client['last_transaction'])) : 'Aucune transaction' ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 text-slate-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p>Aucun client enregistré</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination (si nécessaire) -->
            <?php if (isset($pager) && $pager): ?>
                <div class="flex justify-center mt-4">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function filterClients() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('client-item');
            
            for (let i = 0; i < items.length; i++) {
                const numero = items[i].getAttribute('data-numero');
                if (numero && numero.toLowerCase().includes(filter)) {
                    items[i].style.display = '';
                } else {
                    items[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>