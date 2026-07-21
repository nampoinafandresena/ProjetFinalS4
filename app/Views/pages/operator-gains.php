<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Gains</title>
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
        .stat-card { padding: 24px; border-radius: 24px; background: white; border: 1px solid #f1f5f9; }
        .op-stat-card { padding: 24px; border-radius: 24px; background: white; border: 1px solid #f1f5f9; }
        .section-divider { border: none; border-top: 2px dashed #e2e8f0; margin: 32px 0; }
        .badge-our { background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-other { background: #e2e8f0; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .reversement-card { border-left: 4px solid #f59e0b; }
        .commission-badge { background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="flex items-center gap-3 px-2 py-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center shadow-lg shadow-slate-900/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div><p class="font-bold text-slate-900 leading-tight">Opérateur</p><p class="text-xs text-slate-500">Mobile Money</p></div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-item">Dashboard</a>
            <a href="<?= base_url('admin/operateurs') ?>" class="nav-item">Opérateurs</a>
            <a href="<?= base_url('admin/prefixe') ?>" class="nav-item">Préfixes</a>
            <a href="<?= base_url('admin/bareme-frais') ?>" class="nav-item">Opérations & Frais</a>
            <a href="<?= base_url('operator/gains') ?>" class="nav-item active">Gains</a>
            <a href="<?= base_url('operator/clients') ?>" class="nav-item">Clients</a>
        </nav>
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quitter
        </a>
    </aside>

    <!-- Mobile Header -->
    <header class="lg:hidden bg-white border-b border-slate-100 px-4 py-3 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <span class="font-bold text-slate-900">Opérateur</span>
        </div>
        <a href="<?= base_url('logout') ?>" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </header>
    <nav class="lg:hidden mobile-nav">
        <a href="<?= base_url('admin/dashboard') ?>"><button class="mobile-nav-item">Dashboard</button></a>
        <a href="<?= base_url('admin/operateurs') ?>"><button class="mobile-nav-item">Opérateurs</button></a>
        <a href="<?= base_url('admin/prefixe') ?>"><button class="mobile-nav-item">Préfixes</button></a>
        <a href="<?= base_url('admin/bareme-frais') ?>"><button class="mobile-nav-item">Frais</button></a>
        <a href="<?= base_url('operator/gains') ?>"><button class="mobile-nav-item active">Gains</button></a>
        <a href="<?= base_url('operator/clients') ?>"><button class="mobile-nav-item">Clients</button></a>
    </nav>

    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Rapport des gains</h1>
                <p class="text-slate-500 mt-1">Analyse des frais et commissions collectés</p>
            </div>

            <!-- Statistiques globales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Frais collectés</p>
                    <p class="text-2xl font-bold text-emerald-600"><?= number_format($total_frais ?? 0, 0, ',', ' ') ?> Ar</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Commissions collectées</p>
                    <p class="text-2xl font-bold text-amber-600"><?= number_format($total_commission ?? 0, 0, ',', ' ') ?> Ar</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Volume total</p>
                    <p class="text-2xl font-bold text-slate-900"><?= number_format($total_volume ?? 0, 0, ',', ' ') ?> Ar</p>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Transactions</p>
                    <p class="text-2xl font-bold text-slate-900"><?= $total_transactions ?? 0 ?></p>
                </div>
            </div>

            <!-- Notre opérateur (Telma) -->
            <hr class="section-divider" />
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900"><?= esc($notreOperateur['operateur'] ?? 'Telma') ?> <span class="badge-our">Notre opérateur</span></h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="stat-card">
                        <p class="text-sm text-slate-500 font-medium">Frais collectés</p>
                        <p class="text-2xl font-bold text-emerald-600"><?= number_format($notreOperateur['frais'] ?? 0, 0, ',', ' ') ?> Ar</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-sm text-slate-500 font-medium">Volume traité</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($notreOperateur['volume'] ?? 0, 0, ',', ' ') ?> Ar</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-sm text-slate-500 font-medium">Transactions</p>
                        <p class="text-2xl font-bold text-slate-900"><?= $notreOperateur['transactions'] ?? 0 ?></p>
                    </div>
                    <div class="stat-card bg-emerald-50/50 border-emerald-200">
                        <p class="text-sm text-slate-500 font-medium">Gain total</p>
                        <p class="text-2xl font-bold text-emerald-700"><?= number_format($notreOperateur['frais'] ?? 0, 0, ',', ' ') ?> Ar</p>
                    </div>
                </div>
            </div>

            <!-- Autres opérateurs -->
            <hr class="section-divider" />
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Autres opérateurs <span class="badge-other">Commissions</span></h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <?php if (!empty($autresOperateurs)): ?>
                        <?php foreach ($autresOperateurs as $op): ?>
                            <div class="op-stat-card border-l-4 <?= $op['commission'] > 0 ? 'border-amber-500' : 'border-slate-300' ?>">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-slate-900"><?= esc($op['operateur']) ?></p>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-full">
                                        Commission: <?= number_format($op['commission_pourcentage'], 1) ?>%
                                    </span>
                                </div>
                                <div class="mt-4 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Commissions collectées</span>
                                        <span class="font-bold text-amber-600"><?= number_format($op['commission'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Transactions concernées</span>
                                        <span class="font-semibold text-slate-900"><?= $op['transactions'] ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm pt-2 border-t border-slate-100">
                                        <span class="text-slate-500">Montant à reverser</span>
                                        <span class="font-bold text-amber-700 text-lg"><?= number_format($op['commission'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-2 text-center text-slate-400 py-8">
                            Aucune commission collectée pour les autres opérateurs
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Montants à reverser -->
            <hr class="section-divider" />
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Montants à reverser aux opérateurs</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php if (!empty($montantsAReverser)): ?>
                        <?php foreach ($montantsAReverser as $mr): ?>
                            <div class="op-stat-card reversement-card">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-slate-900"><?= esc($mr['operateur']) ?></p>
                                    <span class="commission-badge"><?= number_format($mr['commission_pourcentage'], 1) ?>%</span>
                                </div>
                                <div class="mt-4 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">À reverser</span>
                                        <span class="font-bold text-amber-600 text-lg"><?= number_format($mr['total_commission'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Montant transféré</span>
                                        <span class="font-semibold text-slate-900"><?= number_format($mr['total_montant_transfere'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Transactions</span>
                                        <span class="font-semibold text-slate-900"><?= $mr['nb_transactions'] ?></span>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-slate-100 text-xs text-slate-400">
                                        Du <?= date('d/m/Y', strtotime($mr['premiere_transaction'])) ?> au <?= date('d/m/Y', strtotime($mr['derniere_transaction'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-3 text-center text-slate-400 py-8">
                            Aucune commission à reverser
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistiques par type -->
            <hr class="section-divider" />
            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-4">Statistiques par type d'opération</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <?php foreach ($statsType as $type): ?>
                        <div class="op-stat-card">
                            <p class="font-semibold text-slate-900 capitalize"><?= esc($type['type_label']) ?></p>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Transactions</span>
                                    <span class="font-semibold text-slate-900"><?= $type['nb_transactions'] ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Volume</span>
                                    <span class="font-semibold text-slate-900"><?= number_format($type['total_volume'], 0, ',', ' ') ?> Ar</span>
                                </div>
                                <div class="flex justify-between text-sm pt-2 border-t border-slate-100">
                                    <span class="text-slate-500">Frais</span>
                                    <span class="font-bold text-emerald-600"><?= number_format($type['total_frais'], 0, ',', ' ') ?> Ar</span>
                                </div>
                                <?php if ($type['total_commission'] > 0): ?>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Commissions</span>
                                        <span class="font-bold text-amber-600"><?= number_format($type['total_commission'], 0, ',', ' ') ?> Ar</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>