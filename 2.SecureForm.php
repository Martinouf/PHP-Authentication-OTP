<?php

// Inclusion des dépendances
require_once dirname(__FILE__).'/vendor/autoload.php';
use OTPHP\TOTP;

/***********************
 * Génération d'un secret
***********************/
$otp = TOTP::create();
$secret = $otp->getSecret();


// Utilisation d'un secret déjà généré
$secret = "JVQXE5DJNZ2HA33UOAYTEMZUGU3DOOBZ";
$secretOutput = "The OTP secret is: {$secret}\n";


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

$otpOutput = "The current OTP is: {$otp->now()}\n";

/***********************
 * Affichage du temps pour information
 ***********************/
// Définition de la zone de temps
date_default_timezone_set('Europe/Paris');
$maintenant = time() ;

// Affichage de maintenant
$dateOutput = date('Y-m-d H:i:s',$maintenant);


/***********************
 * Génération du QrCode
 ***********************/
// Note: You must set label before generating the QR code
$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
$qrCodeOutput = "<img src='{$grCodeUri}'>";



/***********************
 * Fonction de vérification du formulaire
 ***********************/
// Fonction qui renvoie true si login et mot de passe sont corrects
function checkLoginPassword($login, $password)
{
    if ($login=='Martin' && $password=='Martin') return true;
    return false;
}

// Vérifie la valeur OTP
function checkOTP($otp_form): bool
{
    global $otp;

    return $otp->verify($otp_form);
}

$formOutput = '';
// Traitement du formulaire de login:
if (!empty($_POST['login']))
{
    if ( checkLoginPassword($_POST['login'], $_POST['password'] ) && checkOTP( $_POST['otp'] ) )
        $formOutput = "Vous êtes identifié bravo !";
    else
        $formOutput = "BOUUUUH tu t'es trompé";
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>OTP QR CODE</title>
    </head>
    <body>
        <h1>QR Code</h1>
        <div>
            <span>Secret: <?= $secretOutput; ?></span>
        </div>

        <div>
            <span>Current OTP: <?= $otpOutput; ?></span>
        </div>

        <div>
            <span>Date: <?= $dateOutput; ?></span>
        </div>

        <div>
            <?= $qrCodeOutput; ?>
        </div>


        <form method="POST">
            Login: <input type="text" name="login"><br>
            Mot de passe: <input type="password" name="password"><br>
            OTP: <input type="password" name="otp"><br>
            <input type="submit" value="Login"><br>
        </form>
        <hr>
        <h2>Retour du formulaire <br><br> <b><?= $formOutput; ?></b></h2>

    </body>
</html>
