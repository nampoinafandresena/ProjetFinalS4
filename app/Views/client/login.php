<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <title>Mobile Money - Connexion Client</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .btn-primary { background: #0f766e; color: white; padding: 14px 28px; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-primary:hover { background: #0d6b63; transform: scale(0.98); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .input-field { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 500; transition: all 0.2s; }
        .input-field:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2); }
        .demo-account { padding: 8px 12px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.2s; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .demo-account:hover { background: #ecfdf5; border-color: #0f766e; }
        .demo-account:hover .demo-tag { opacity: 1; }
        .demo-tag { opacity: 0; font-size: 12px; font-weight: 600; color: #0f766e; transition: opacity 0.2s; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border: 1px solid #fecaca; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border: 1px solid #a7f3d0; }
        .btn-back { background: transparent; color: #475569; padding: 8px 16px; border-radius: 10px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; }
        .btn-back:hover { background: #f1f5f9; }
        .badge-operateur { background: #e2e8f0; color: #475569; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-50 via-emerald-50/40 to-teal-50/30">
        <div class="w-full max-w-md">
            <!-- Bouton Retour -->
            <div class="mb-4">
                <a href="<?= base_url() ?>" class="btn-back">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>

            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-2xl shadow-emerald-500/30 mb-4 animate-float">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v-5m0 0V8m0 5h5m-5 0H7m6-9H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Espace Client</h1>
                <p class="text-slate-500 mt-2 text-center">Connectez-vous avec votre numéro</p>
            </div>

            <!-- Messages Flash -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white p-8">
                <form action="<?= base_url('client/login') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro de téléphone</label>
                            <div class="flex gap-2">
                                <select name="prefix" id="prefix" class="px-3 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                                    <?php foreach ($prefixes as $p): ?>
                                        <option value="<?= $p['prefixes'] ?>">
                                            <?= $p['prefixes'] ?> 
                                            <span class="badge-operateur"><?= $p['operateur'] ?? 'Opérateur' ?></span>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="tel" name="numero" id="numero" placeholder="1234567" maxlength="7" class="flex-1 input-field" oninput="this.value=this.value.replace(/\D/g,'')" required />
                            </div>
                            <p class="text-xs text-slate-400 mt-2">📱 Entrez un numéro existant - Les comptes sont pré-créés</p>
                        </div>

                        <button type="submit" class="btn-primary" id="loginBtn">
                            Se connecter
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Comptes de démonstration -->
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Comptes de démonstration</p>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="demo-account" onclick="fillDemo('033','0000001')">
                            <div>
                                <span class="text-sm font-semibold text-slate-900">0330000001</span>
                                <p class="text-xs text-slate-500">Client - 5 000 Ar</p>
                            </div>
                            <span class="demo-tag">Utiliser</span>
                        </div>
                        <div class="demo-account" onclick="fillDemo('037','000002')">
                            <div>
                                <span class="text-sm font-semibold text-slate-900">037000002</span>
                                <p class="text-xs text-slate-500">Client - 2 500 Ar</p>
                            </div>
                            <span class="demo-tag">Utiliser</span>
                        </div>
                        <div class="demo-account" onclick="fillDemo('033','0000003')">
                            <div>
                                <span class="text-sm font-semibold text-slate-900">0330000003</span>
                                <p class="text-xs text-slate-500">Client - 0 Ar</p>
                            </div>
                            <span class="demo-tag">Utiliser</span>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-200">
                        <p class="text-xs text-amber-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            ⚠️ Seuls les comptes existants peuvent se connecter
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillDemo(prefix, phone) {
            document.getElementById('prefix').value = prefix;
            document.getElementById('numero').value = phone;
            document.getElementById('loginForm').submit();
        }

        // Gestion du loading
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="inline-block animate-spin w-5 h-5 border-2 border-white border-t-transparent rounded-full mr-2"></span> Connexion...';
        });
    </script>
</body>
</html>