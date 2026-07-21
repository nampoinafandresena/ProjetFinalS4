<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Opérateurs</title>
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
        .btn-primary { background: #0f766e; color: white; padding: 8px 16px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { background: #0d6b63; }
        .input-field { padding: 8px 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; width: 80px; }
        .input-field:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
        .badge-telma { background: #d1fae5; color: #065f46; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .badge-other { background: #e2e8f0; color: #475569; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 16px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 12px; border: 1px solid #fca5a5; margin-bottom: 16px; }
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
            <a href="<?= base_url('admin/operateurs') ?>" class="nav-item active">Opérateurs</a>
            <a href="<?= base_url('admin/prefixe') ?>" class="nav-item">Préfixes</a>
            <a href="<?= base_url('admin/bareme-frais') ?>" class="nav-item">Opérations & Frais</a>
            <a href="<?= base_url('operator/gains') ?>" class="nav-item">Gains</a>
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
        <a href="<?= base_url('admin/operateurs') ?>"><button class="mobile-nav-item active">Opérateurs</button></a>
        <a href="<?= base_url('admin/prefixe') ?>"><button class="mobile-nav-item">Préfixes</button></a>
        <a href="<?= base_url('admin/bareme-frais') ?>"><button class="mobile-nav-item">Frais</button></a>
        <a href="<?= base_url('operator/gains') ?>"><button class="mobile-nav-item">Gains</button></a>
        <a href="<?= base_url('operator/clients') ?>"><button class="mobile-nav-item">Clients</button></a>
    </nav>

    <!-- Main Content -->
    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <!-- Messages flash -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success">✅ <?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error">❌ <?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">Gestion des opérateurs</h1>
                <p class="text-slate-500 mt-1">Configurez les commissions pour les transferts vers chaque opérateur</p>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-700">
                <p class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    La commission est un pourcentage (%) prélevé sur les transferts vers cet opérateur.
                    <br><strong>Telma</strong> = 0% (notre opérateur, utilise les frais fixes)
                </p>
            </div>

            <!-- Liste des opérateurs -->
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Opérateur</th>
                            <th class="px-6 py-4">Préfixes</th>
                            <th class="px-6 py-4 text-center">Commission actuelle</th>
                            <th class="px-6 py-4 text-center">Nouvelle commission</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($operateurs as $op): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-slate-900"><?= esc($op['operateur']) ?></span>
                                        <?php if ($op['operateur'] == 'Telma'): ?>
                                            <span class="badge-telma">Notre opérateur</span>
                                        <?php else: ?>
                                            <span class="badge-other">Autre</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <?= $op['nb_prefixes'] ?? 0 ?> préfixes
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold <?= $op['commission'] > 0 ? 'text-amber-600' : 'text-emerald-600' ?>">
                                        <?= number_format($op['commission'], 1) ?>%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="<?= base_url('admin/operateur/update/' . $op['id']) ?>" method="POST" class="flex items-center justify-center gap-2">
                                        <?= csrf_field() ?>
                                        <input type="number" 
                                               name="commission" 
                                               value="<?= $op['commission'] ?>" 
                                               step="0.1" 
                                               min="0" 
                                               max="100"
                                               class="input-field text-center"
                                               <?= $op['operateur'] == 'Telma' ? 'disabled' : '' ?> />
                                        <span class="text-xs text-slate-400">%</span>
                                        <button type="submit" class="btn-primary text-sm" <?= $op['operateur'] == 'Telma' ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>>
                                            Modifier
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if ($op['operateur'] != 'Telma'): ?>
                                        <span class="text-xs text-slate-400">
                                            <?php if ($op['commission'] > 0): ?>
                                                <span class="text-amber-600">✅ Commission active</span>
                                            <?php else: ?>
                                                <span class="text-slate-400">⚠️ Commission à 0%</span>
                                            <?php endif; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">🔒 Fixe</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Explication du calcul -->
            <div class="bg-white rounded-3xl border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-900 mb-3">📊 Comment fonctionne la commission ?</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <p><strong>Exemple :</strong> Transfert de <strong>1 500 Ar</strong> de <strong>Telma</strong> vers <strong>Airtel</strong></p>
                    <div class="bg-slate-50 rounded-xl p-4 space-y-1 font-mono text-xs">
                        <div>Commission Airtel = 1.5% × 1500 = <span class="text-amber-600 font-bold">22.5 Ar</span></div>
                        <div>Frais Telma = <span class="text-emerald-600 font-bold">200 Ar</span> (selon barème)</div>
                        <div class="border-t border-slate-200 pt-2">Total débité = 1500 + 200 + 22.5 = <span class="text-slate-900 font-bold">1 722.5 Ar</span></div>
                        <div class="text-xs text-slate-400">Le destinataire reçoit 1 500 Ar</div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-3">
                        <div class="bg-emerald-50 p-3 rounded-xl text-center">
                            <p class="text-xs text-emerald-600">Gain Telma</p>
                            <p class="font-bold text-emerald-700">200 Ar</p>
                            <p class="text-xs text-slate-400">(frais)</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-xl text-center">
                            <p class="text-xs text-amber-600">Gain Airtel</p>
                            <p class="font-bold text-amber-700">22.5 Ar</p>
                            <p class="text-xs text-slate-400">(commission)</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl text-center">
                            <p class="text-xs text-slate-500">À reverser</p>
                            <p class="font-bold text-slate-700">22.5 Ar</p>
                            <p class="text-xs text-slate-400">à Airtel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>