<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Opérations & Frais</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 256px; background: white; border-right: 1px solid #f1f5f9; padding: 20px; display: none; flex-direction: column; z-index: 40; }
        @media (min-width: 1024px) { .sidebar { display: flex; } }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 12px; font-size: 14px; font-weight: 500; color: #64748b; transition: all 0.2s; width: 100%; border: none; background: transparent; cursor: pointer; }
        .nav-item:hover { background: #f1f5f9; }
        .nav-item.active { background: #0f172a; color: white; }
        .btn-primary { background: #0f766e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #0d6b63; transform: scale(0.98); }
        .btn-danger { background: #dc2626; color: white; padding: 6px 14px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; font-size: 12px; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-warning { background: #d97706; color: white; padding: 6px 14px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; font-size: 12px; }
        .btn-warning:hover { background: #b45309; }
        .mobile-nav { display: flex; gap: 4px; padding: 8px 16px; background: white; border-bottom: 1px solid #f1f5f9; overflow-x: auto; }
        @media (min-width: 1024px) { .mobile-nav { display: none; } }
        .mobile-nav-item { padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap; border: none; background: #f1f5f9; color: #64748b; cursor: pointer; }
        .mobile-nav-item.active { background: #0f172a; color: white; }
        .op-card { background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; }
        .op-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .bracket-item { padding: 16px 24px; border-bottom: 1px solid #f8fafc; display: flex; align-items: center; gap: 16px; transition: background 0.2s; flex-wrap: wrap; }
        .bracket-item:hover { background: #f8fafc; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 50; display: none; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: white; border-radius: 24px; max-width: 500px; width: 100%; padding: 24px; animation: scaleIn 0.2s ease-out; max-height: 90vh; overflow-y: auto; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .input-field { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; }
        .input-field:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
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
            <a href="<?= base_url('admin/prefixe') ?>" class="nav-item">Préfixes</a>
            <a href="<?= base_url('admin/bareme-frais') ?>" class="nav-item active">Opérations & Frais</a>
        </nav>
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quitter
        </a>
    </aside>

    <!-- Mobile Header & Nav -->
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
        <a href="<?= base_url('admin/prefixe') ?>"><button class="mobile-nav-item">Préfixes</button></a>
        <a href="<?= base_url('admin/bareme-frais') ?>"><button class="mobile-nav-item active">Frais</button></a>
    </nav>

    <main class="lg:ml-64 p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="space-y-6">
            <!-- Messages flash -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">Opérations & Frais</h1>
                <p class="text-slate-500 mt-1">Configurez les types d'opérations et leurs barèmes de frais</p>
            </div>

            <!-- Sélecteur d'opérateur -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Filtrer par opérateur</label>
                <select id="operateurSelect" class="w-full md:w-64 px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                    <option value="all">Tous les opérateurs</option>
                    <?php foreach ($operateurs as $operateur): ?>
                        <option value="<?= $operateur['id'] ?>"><?= esc($operateur['operateur']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Dépôt - sans frais -->
            <div class="op-card">
                <div class="op-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                        <div><p class="font-semibold text-slate-900">Dépôt</p><p class="text-xs text-slate-500">Aucun frais</p></div>
                    </div>
                    <span class="text-xs text-slate-400 bg-slate-50 px-3 py-1 rounded-full">Sans frais</span>
                </div>
                <div class="px-6 py-8 text-center text-slate-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Opération sans frais - aucune configuration nécessaire
                </div>
            </div>

            <!-- Retrait -->
            <div class="op-card">
                <div class="op-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </div>
                        <div><p class="font-semibold text-slate-900">Retrait</p><p class="text-xs text-slate-500">Frais applicables</p></div>
                    </div>
                    <button class="btn-primary text-sm" onclick="openModal('retrait')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter
                    </button>
                </div>
                <?php 
                $retraitId = null;
                foreach ($types as $type) {
                    if ($type['label'] == 'retrait') {
                        $retraitId = $type['id'];
                        break;
                    }
                }
                $hasRetrait = false;
                foreach ($baremes as $bareme) {
                    if ($bareme['id_type_operation'] == $retraitId) {
                        $hasRetrait = true;
                        ?>
                        <div class="bracket-item" data-operateur="<?= $bareme['id_operateur'] ?? '' ?>">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900"><?= number_format($bareme['min'], 0, ',', ' ') ?> - <?= number_format($bareme['max'], 0, ',', ' ') ?> Ar</p>
                                <p class="text-xs text-slate-500"><?= esc($bareme['operateur'] ?? 'Inconnu') ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-amber-600"><?= number_format($bareme['frais'], 0, ',', ' ') ?> Ar</p>
                                <p class="text-xs text-slate-400">Frais</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openEditModal(<?= $bareme['id'] ?>, <?= $bareme['min'] ?>, <?= $bareme['max'] ?>, <?= $bareme['frais'] ?>, <?= $bareme['id_type_operation'] ?>, <?= $bareme['id_operateur'] ?>)" class="btn-warning" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="<?= base_url('admin/bareme-frais/delete/' . $bareme['id']) ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tranche ?')" class="inline">
                                    <button type="submit" class="btn-danger" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
                if (!$hasRetrait) {
                    ?>
                    <div class="px-6 py-4 text-center text-slate-400 text-sm">
                        Aucune tranche configurée pour le retrait
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Transfert -->
            <div class="op-card">
                <div class="op-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div><p class="font-semibold text-slate-900">Transfert</p><p class="text-xs text-slate-500">Frais applicables</p></div>
                    </div>
                    <button class="btn-primary text-sm" onclick="openModal('transfert')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter
                    </button>
                </div>
                <?php 
                $transfertId = null;
                foreach ($types as $type) {
                    if ($type['label'] == 'transfert') {
                        $transfertId = $type['id'];
                        break;
                    }
                }
                $hasTransfert = false;
                foreach ($baremes as $bareme) {
                    if ($bareme['id_type_operation'] == $transfertId) {
                        $hasTransfert = true;
                        ?>
                        <div class="bracket-item" data-operateur="<?= $bareme['id_operateur'] ?? '' ?>">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900"><?= number_format($bareme['min'], 0, ',', ' ') ?> - <?= number_format($bareme['max'], 0, ',', ' ') ?> Ar</p>
                                <p class="text-xs text-slate-500"><?= esc($bareme['operateur'] ?? 'Inconnu') ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-amber-600"><?= number_format($bareme['frais'], 0, ',', ' ') ?> Ar</p>
                                <p class="text-xs text-slate-400">Frais</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openEditModal(<?= $bareme['id'] ?>, <?= $bareme['min'] ?>, <?= $bareme['max'] ?>, <?= $bareme['frais'] ?>, <?= $bareme['id_type_operation'] ?>, <?= $bareme['id_operateur'] ?>)" class="btn-warning" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="<?= base_url('admin/bareme-frais/delete/' . $bareme['id']) ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tranche ?')" class="inline">
                                    <button type="submit" class="btn-danger" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
                if (!$hasTransfert) {
                    ?>
                    <div class="px-6 py-4 text-center text-slate-400 text-sm">
                        Aucune tranche configurée pour le transfert
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Modal Ajout -->
    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-slate-900 mb-4" id="modalTitle">Nouveau barème</h3>
            <form action="<?= base_url('admin/bareme-frais/add') ?>" method="POST">
                <div class="space-y-5">
                    <input type="hidden" name="id_type_operation" id="id_type_operation" value="">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Opérateur</label>
                        <select name="id_operateur" id="id_operateur" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all" required>
                            <?php foreach ($operateurs as $operateur): ?>
                                <option value="<?= $operateur['id'] ?>"><?= esc($operateur['operateur']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Min (Ar)</label>
                            <input type="number" name="min" placeholder="100" class="input-field" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Max (Ar)</label>
                            <input type="number" name="max" placeholder="1000" class="input-field" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Frais (Ar)</label>
                        <input type="number" name="frais" placeholder="50" class="input-field" required />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-all">Annuler</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Édition -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Modifier le barème</h3>
            <form action="" method="POST" id="editForm">
                <div class="space-y-5">
                    <input type="hidden" name="id_type_operation" id="edit_id_type_operation" value="">
                    <input type="hidden" name="id_operateur" id="edit_id_operateur" value="">
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Min (Ar)</label>
                            <input type="number" name="min" id="edit_min" placeholder="100" class="input-field" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Max (Ar)</label>
                            <input type="number" name="max" id="edit_max" placeholder="1000" class="input-field" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Frais (Ar)</label>
                        <input type="number" name="frais" id="edit_frais" placeholder="50" class="input-field" required />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeEditModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-all">Annuler</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Récupérer l'ID du type d'opération
        let retraitId = null;
        let transfertId = null;
        <?php foreach ($types as $type): ?>
            <?php if ($type['label'] == 'retrait'): ?>
                retraitId = <?= $type['id'] ?>;
            <?php endif; ?>
            <?php if ($type['label'] == 'transfert'): ?>
                transfertId = <?= $type['id'] ?>;
            <?php endif; ?>
        <?php endforeach; ?>

        // Ajout
        function openModal(type) {
            const title = document.getElementById('modalTitle');
            const typeInput = document.getElementById('id_type_operation');
            
            if (type === 'retrait') {
                title.textContent = 'Nouveau barème - Retrait';
                typeInput.value = retraitId;
            } else if (type === 'transfert') {
                title.textContent = 'Nouveau barème - Transfert';
                typeInput.value = transfertId;
            }
            
            document.getElementById('modal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('modal').classList.remove('active');
        }

        // Édition
        function openEditModal(id, min, max, frais, typeOperationId, operateurId) {
            document.getElementById('editForm').action = '<?= base_url('admin/bareme-frais/update/') ?>' + id;
            document.getElementById('edit_min').value = min;
            document.getElementById('edit_max').value = max;
            document.getElementById('edit_frais').value = frais;
            document.getElementById('edit_id_type_operation').value = typeOperationId;
            document.getElementById('edit_id_operateur').value = operateurId;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Fermer les modals avec Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
                closeEditModal();
            }
        });

        // Fermer en cliquant à l'extérieur
        document.getElementById('modal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeModal();
        });
        document.getElementById('editModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeEditModal();
        });

        // Filtre par opérateur
        document.getElementById('operateurSelect')?.addEventListener('change', function() {
            const operateurId = this.value;
            const items = document.querySelectorAll('.bracket-item');
            
            items.forEach(item => {
                const itemOperateur = item.dataset.operateur || '';
                if (operateurId === 'all' || itemOperateur == operateurId) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>