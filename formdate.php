<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>timeline</title>
    <!-- style communs -->
    <link rel="stylesheet" href="style.css" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet" />

    <!-- style spécifiques -->
    <link rel="stylesheet" href="timelineStyle.css" />
</head>

<body>
    <?php
    function testpost(&$dateerr, &$contexterr, &$dateerrbis, &$datebeg, &$dateend, &$context)
    {
        if (empty($_POST["datebeg"])) {
            $dateerr = "une date est nécessaire";
        } else {
            $datebeg = strtotime(($_POST["datebeg"]));
        }
        if (!empty($_POST["dateend"])) {
            $dateend = strtotime(($_POST["dateend"]));
            if ($dateend - $datebeg < 0) {
                $dateerrbis = "la date de fin n'est pas correcte";
            }
        }
        if (empty($_POST["context"])) {
            $contexterr = "un context est nécessaire";
        } else {
            $context = htmlspecialchars($_POST["context"]);
        }
    }

    function updatedates($filename, $separateur, $arraytoplace)
    {
        // récupération de la donnée et stockage dans un array
        $file = fopen($filename, "r") or die("fichier impossible à ouvrir");
        $dates = [];
        $temparray = [];
        $tarray = [];
        $i = 0;
        $placed = false;

        while (!feof($file)) {
            $temp = fgets($file);
            if (!empty($temp)) {
                $temparray = explode($separateur, $temp);
                $n = 0;
                foreach ($temparray as $act) {
                    if ($n < 2) {
                        // les 2 premiers sont des dates
                        $tarray[$n] = strtotime($act);
                    } elseif ($n === 2) {
                        // le 3ème est un texte
                        $tarray[$n] = $act;
                    } else {
                        // si un petit malin a mis le séparateur dans son context
                        $tarray[2] .= $separateur . $act;
                    }
                    $n++;
                }
                if (!$placed && $tarray[0] - $arraytoplace[0] > 0) {
                    $dates[$i] = $arraytoplace;
                    $placed = true;
                    $i++;
                }
                $dates[$i] = $tarray;
                $i++;
            }
        }
        fclose($file);

        // cas où il n'y avait rien dans le fichier ou qu'il soit en tout dernier
        if (!$placed) {
            $dates[$i] = $arraytoplace;
        }
        return $dates;
    }

    function writedates($filename, $separateur, $dates)
    {
        $file = fopen($filename, "w");
        $temp = "";
        foreach ($dates as $line) {
            $temp = date('F j, Y H:i', $line[0]) . $separateur .
                date('F j, Y H:i', $line[1]) . $separateur .
                $line[2];
            fwrite($file, $temp);
        }
        fclose($file);
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dateerr = $contexterr = $dateerrbis = "";
        $datebeg = $dateend = $context = "";

        testpost($dateerr, $contexterr, $dateerrbis, $datebeg, $dateend, $context);

        if (
            $_SERVER["REQUEST_METHOD"] == "POST"
            && $dateerr === $dateerrbis
            && $dateerrbis === $contexterr
            && $contexterr === ""
        ) {
            // on intialise les variables
            include "important.php";
            $arraytoplace = [];

            if ($dateend === "") {
                $arraytoplace = [$datebeg, $datebeg, $context . "\n"];
            } else {
                $arraytoplace = [$datebeg, $dateend, $context . "\n"];
            }
            $dates = updatedates($filename, $separateur, $arraytoplace);


            // maintenant on va écrire dans le fichier
            writedates($filename, $separateur, $dates);
        }
    }

    ?>
    <form action="formdate.php" method="POST">
        <p>date de début: <input type="datetime-local" name="datebeg"><?php echo $dateerr; ?></p>
        <p>date de fin: <input type="datetime-local" name="dateend"><?php echo $dateerrbis; ?></p>
        <p>context de la date: <textarea name="context"></textarea><?php echo $contexterr; ?></p>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php

    ?>


    <?php
    include "timeline.php";
    ?>
</body>

</html>