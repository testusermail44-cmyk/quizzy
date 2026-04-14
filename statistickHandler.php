<?php
function loadStat($statistick, $full = true){
    ?>
    <div class="stat-container">
            <?php 
                $place = '';
                $count = 1;
                foreach($statistick as $s){
                    switch($count){
                        case 1: 
                            $place = ' gold'; 
                            break;
                        case 2: 
                            $place = ' silver';
                            break;
                        case 3: 
                            $place = ' bronze';
                            break;
                        default: $place = '';
                    }
                    ?>
                        <div class="place-container">
                            <div class="place<?=$place?>"><?=$count?></div>
                            <div class="place-info">
                                <span class="place-text"><?=$s['username'].' '.$s['surname']?></span>
                                <span class="place-text">Рахунок: <?=$s['scores']?></span>
                            </div>
                        </div>
                    <?php
                    $count++;
                    if (!$full)
                        if ($count > 3)
                            break;
                }
            ?>
        </div>
    <?php
    }
?>