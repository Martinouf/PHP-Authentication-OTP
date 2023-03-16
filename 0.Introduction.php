<?php

// Inclusion des dépendances
require_once dirname(__FILE__).'/vendor/autoload.php';
use OTPHP\TOTP;

/***********************
 * Génération d'un secret
***********************/
$otp = TOTP::create();

// Génération à la volée
$secret = $otp->getSecret();

// ou utilisation d'un secret déjà généré par nos soins (pour les tests)
$secret = "JVQXE5DJNZ2HA33UOAYTEMZUGU3DOOBZ";

echo "The OTP secret is: {$secret}\n";



/***********************
 * Création du TOTP avec des informations précises
 ***********************/
$otp = TOTP::create(
    $secret,                   // secret utilisé (généré plus haut)
    15,                 // période de validité
    'sha256',           // Algorithme utilisé
    8                   // 8 digits
);
$otp->setLabel('TP'); // The label
$otp->setIssuer('Martin');
$otp->setParameter('image', 'C:\Users\binantm\OneDrive - Fénelon Notre-Dame\Images\Saved Pictures\SLAM1\Article-20-900x510.jpg'); // FreeOTP can display image
echo "The current OTP is: {$otp->now()}\n";

/***********************
 * Affichage du temps pour information
 ***********************/
// Définition de la zone de temps
date_default_timezone_set('Europe/Paris');
$maintenant = time() ;

// Affichage de maintenant
echo date('Y-m-d H:i:s',$maintenant);