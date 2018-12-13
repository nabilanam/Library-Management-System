<?php

function modal($header, $question){
    echo '
    <div class="ui mini test modal">
    <div class="header">'.$header.'</div>
    <div class="content">
      <p>'.$question.'</p>
    </div>
    <div class="actions">
      <div class="ui negative button">
        No
      </div>
      <div class="ui positive right labeled icon button">
        Yes
      </div>
    </div>
  </div>
    ';
}