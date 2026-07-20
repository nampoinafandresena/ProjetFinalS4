# Projet Final S4
ETU004017 - ETU004255
- tag par version 
    -rehfa manao commit : omena marque hoe V1

- [ok] creation de Taches.mg
- Base: base.sql
    - operateur (id, numero: 0335261, solde)
    - type_operation(id, label) (dépôt, retrait, transfert)
    - bareme_frais(id, min, max, frais) -> switch case
    - historiques(id_operateur, type_mvt, montant, date_transaction)

- Model
    - model par tables

- Cote operateur
    - configuration des préfixes valable de l’opérateur (ex: 033 et 037)


- Cote client
    - login automatique
        - Model
        - View
            - donnees
            - CSS
        - Controller
        - BASE