<main>
    <!-- Schedule -->
    <div class="timeline">
        <?php

        include "important.php";

        function recupdates($filename, $separateur)
        {
            // récupération de la donnée et stockage dans un array
            $file = fopen($filename, "r") or die("fichier impossible à ouvrir");
            $dates = [];
            $i = 0;
            $temparray = [];
            $tarray = [];
            while (!feof($file)) {
                $temp = fgets($file);

                if (!empty($temp)) {
                    $temparray = explode($separateur, $temp);
                    $n = 0;
                    foreach ($temparray as $act) {
                        if ($n <= 2) {
                            $tarray[$n] = $act;
                        } else {
                            // si un petit malin a mis le séparateur dans son context
                            $tarray[3] .= $separateur . $act;
                        }
                        $n++;
                    }
                    $dates[$i] = $tarray;
                    $i++;
                }
            }
            fclose($file);
            return $dates;
        }


        $dates = recupdates($filename, $separateur);
        $title = "";
        $context = "";

        foreach ($dates as $act) {
            if ($act[0] === $act[1]) {
                $title = $act[0];
            } else {
                $title = $act[0] . " to " . $act[1];
            }
            $context = $act[2];
            echo    "<div class='cont'>
                            <div class='dateBox before' date='$act[0]' datebis='$act[1]'>
                                <h5>$title</h5>
                                <p>$context</p>
                            </div>
                        </div>
                        ";
        }

        ?>
    </div>

    <script type="text/javascript" src="timeline.js" defer></script>
    <main>