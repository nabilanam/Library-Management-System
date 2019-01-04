<?php
function printItem($header, $icon, $subheader, $link='#'){
    echo '<div class="column">
                <div class="ui raised segment">
                    <div class="two column stackable padded ui grid">
                        <div class="six wide column">
                            <div class="ui icon header">
                                <i class="ui icon circular '.$icon.' "></i>
                            </div>
                        </div>
                        <div class="nine wide column">
                            <div class="ui icon header ">
                                <a class="ui center huge aligned header number">'.$header.'</a>
                                <div class="">
                                    <div class="sub header ">'.$subheader.'</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ui bottom attached label">
                        <a href="'.$link.'">See all <i class="icon arrow right"></i></a>
                    </div>
                </div>
            </div>';
}