<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore di sconto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

<?php
$prodotto = "";
$prezzo = "";
$sconto = "";
$err_prodotto = "";
$err_prezzo = "";
$err_sconto = "";
$calcolato = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodotto = $_POST['prodotto'];
    $prezzo = $_POST['prezzo'];
    $sconto = $_POST['sconto'];

    if ($prodotto == "") {
        $err_prodotto = "Il prodotto è obbligatorio";
    } elseif (isset($prodotto[30])) {
        $err_prodotto = "Massimo 30 caratteri";
    }

    if ($prezzo == "" || $prezzo <= 0) {
        $err_prezzo = "Il prezzo deve essere maggiore di 0";
    }

    if ($sconto == "" || $sconto < 1 || $sconto > 90) {
        $err_sconto = "Lo sconto deve essere da 1 a 90";
    }

    if ($err_prodotto == "" && $err_prezzo == "" && $err_sconto == "") {
        $calcolato = true;

        $scontoeu = ($prezzo * $sconto) / 100;
        $scontoeu = round($scontoeu, 2);
        $prscon = $prezzo - $scontoeu;
        $prscon = round($prscon, 2);

        if ($sconto < 20) {
            $classe = "piccolo";
            $testo = "PICCOLO";
        } elseif ($sconto <= 49) {
            $classe = "buono";
            $testo = "BUONO";
        } else {
            $classe = "super";
            $testo = "SUPER";
        }

        if ($myfile = @fopen("log.csv", "a")) {
            $record = implode("; ", [
                $record = date("d/m/Y"),
                $record = date("G:i:s"),
                $record = $_SERVER['REMOTE_ADDR'],
                $record = $prodotto,
                $record = $prezzo,
                $record = $sconto,
                $record = $scontoeu,
                $record = $prscon,
                $record = $testo
            ]) . ";\n";
            fwrite($myfile, $record);
            fclose($myfile);
        }
    }
}
?>

    <h1>Calcolatore di sconto</h1>

    <form method="post" action="index.php">

        <label for="prodotto">Prodotto</label>
        <input type="text" id="prodotto" name="prodotto" >
        <?php if ($err_prodotto != ''): ?>
            <p class="msg-errore">
                <?php echo $err_prodotto; ?>
            </p>
        <?php endif; ?>

        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" >
        <?php if ($err_prezzo != ''): ?>
            <p class="msg-errore">
                <?php echo $err_prezzo; ?>
            </p>
        <?php endif; ?>

        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto" >
        <?php if ($err_sconto != ''): ?>
            <p class="msg-errore">
                <?php echo $err_sconto; ?>
            </p>
        <?php endif; ?>

        <button type="submit">Calcola</button>
    </form>

    <?php if ($calcolato): ?>
        <div class="card <?php echo $classe; ?>">
            <h2><?php echo $prodotto; ?></h2>
            <p>Prezzo: <?php echo $prezzo; ?> € &middot; Sconto: <?php echo $sconto; ?> %</p>
            <p>Prezzo finale: <?php echo $prscon; ?> €</p>
            <p>Risparmio: <?php echo $scontoeu; ?> €</p>
            <p><?php echo $testo; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($myfile = @fopen("log.csv", "r")): ?>
        <h2>Storico</h2>
        <?php while ($riga = fgets($myfile)): ?>
            <p><?php echo $riga; ?></p>
        <?php endwhile; ?>
        <?php fclose($myfile); ?>
    <?php endif; ?>

</body>
</html>