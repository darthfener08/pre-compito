<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore di sconto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <h1>Calcolatore di sconto</h1>
    <form method="post" action="index.php">
        <label for="prodotto">Prodotto</label>
        <input type="text" id="prodotto" name="prodotto" require>
        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" class="errore">
        <p class="msg-errore">Il prezzo deve essere maggiore di 0</p>
        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto">
        <button type="submit">Calcola</button>


        
    </form>

    


    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): 
        
        $prodotto = $_POST['prodotto'];
        $prezzo = $_POST['prezzo'];
        $sconto = $_POST['sconto'];

        $scontoeu = $prezzo * $sconto;
        $scontoeu = $scontoeu / 100;
        $prscon = $prezzo - $scontoeu;

        if ($sconto < 20 ) {
            $classe = "piccolo";
            $testo = "Piccolo";
        } elseif ($sconto <= 49 ) {
            $classe = "buono";
            $testo = "Buono";
        } else ($sconto > 50  ) {
            $classe = "super";
            $testo = "super";
        } 

        if ($myfile = @fopen("log.csv", "a")) {
            $record = implode("; ", [
            $record = date("d/m/Y"),
            $record = date("G:i:s"),
            $record = $_SERVER['REMOTE_ADDR'],
            $record = $nome,
            $record = $altezza_cm,
            $record = $peso,
            $record = $bmi_arrotondato,
            $record = $testo
            ]) . ";\n";
            fwrite($myfile, $record);
            fclose($myfile);
        }
    ?>

        <h1>Il tuo BMI</h1>

        <div class="card <?= $classe ?>">

            <h2><?= $prodotto ?></h2>

            <p>Prezzo <?= $prezzo ?> € &middot; Sconto <?= $sconto ?> %</p>

            <div class=""><?= $prscon ?></div>

            <div class="fascia-testo"><?= $testo ?></div>
            
        </div>

        <a href="index.php" class="link-indietro">Calcola di nuovo</a>

    <?php else: ?>

        <h1>Calcolatore BMI</h1>

        <div class="box-form">
            <form action="index.php" method="post">
                <div class="gruppo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="gruppo">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="peso" name="peso" step="0.1" required>
                </div>

                <div class="gruppo">
                    <label for="altezza">Altezza (cm)</label>
                    <input type="number" id="altezza" name="altezza" required>
                </div>

                <button type="submit">Calcola</button>
            </form>
        </div>

    <?php endif; ?>
</body>
</html>