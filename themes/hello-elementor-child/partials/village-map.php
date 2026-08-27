<div class="rangeford-village-map">
    <div class="rangeford-village-map__map-image">
        <?= file_get_contents(get_field('village_map_image', 'option')['url']) ?>
    </div>
    <div class="rangeford-village-map__markers">
        <?php foreach($markers as $marker): ?>
            <a href="<?= $marker['village']['permalink'] ?>" class="rangeford-village-map__marker" style="left: <?= $marker['marker_x'] ?>; top: <?= $marker['marker_y'] ?>;">
                <div class="rangeford-village-map__marker-container">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5.1 6.39">
                        <path class="cls-2" d="M3.03,6.25s0,0,0,0l-.13.15c-.31-.34-.6-.7-.88-1.06l-.18-.24s0,0,0,0c-.78-1.04-1.13-1.83-1.13-2.39C.69,1.48,1.68.49,2.9.49s2.2.99,2.2,2.21c0,.56-.36,1.35-1.13,2.39,0,0,0,0,0,0l-.18.24s0,0,0,0c-.22.29-.47.59-.75.91Z"/>
                        <path class="cls-1 <?php if(!$marker['village']['future_village']): ?>future-village<?php endif ?>" d="M2.49,5.91s0,0,0,0l-.13.15c-.31-.34-.6-.7-.88-1.06l-.18-.24s0,0,0,0C.52,3.72.16,2.93.16,2.37.16,1.15,1.14.16,2.36.16s2.2.99,2.2,2.21c0,.56-.36,1.35-1.13,2.39,0,0,0,0,0,0l-.18.24s0,0,0,0c-.22.29-.47.59-.75.91Z"/>
                    </svg>
                    <div class="rangeford-village-map__marker-title <?php if(stripos($marker['village']['initials'], '<br>') !== false): ?>smaller<?php endif ?>"><?= $marker['village']['initials'] ?></div>
                </div>
            </a>
        <?php endforeach ?>
    </div>
</div>

<style type="text/css">
    .rangeford-village-map{
        position: relative;
        margin-bottom: 50px;
    }
    
    .rangeford-village-map__map-image svg{
        max-width: 100%;
        height: auto;
    }

    .rangeford-village-map__markers{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
    }

    .rangeford-village-map__marker-title{
        font-family: "Optima", Sans-serif;
        color: white;
        font-size: 1rem;
        text-align: center;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding-top: 20%;
        padding-right: 7%;
    }
    
    .rangeford-village-map__marker-title.smaller{
        font-size: 0.8rem;
        line-height: 1;
    }
    }

    @media (max-width: 1366px){
        .rangeford-village-map__marker-title{
            font-size: 10px;
        }
    }

    @media (max-width: 1200px){
        .rangeford-village-map__marker-title{
            font-size: 18px;
        }
    }

    @media (max-width: 600px){
        .rangeford-village-map__marker-title{
            font-size: 14px;
        }
    }

    @media (max-width: 450px){
        .rangeford-village-map__marker-title{
            font-size: 11px;
        }
    }

    @media (max-width: 320px){
        .rangeford-village-map__marker-title{
            font-size: 9px;
        }
    }

    .rangeford-village-map__marker{
        display: block;
        position: absolute;
        width: 1%;
        height: auto;
    }

    .rangeford-village-map__marker-container{
        width: 750%;
        height: auto;
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    #Layer_1{
        width: 100%;
        height: auto;
    }

    .cls-1 {
        fill: #c2bb86;
        stroke: #fff;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: .31px;
      }

      .cls-1.future-village{
        fill: #003655;
      }

      .cls-2 {
        fill: #212121;
        opacity: .5;
      }

</style>