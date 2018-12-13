<?php

if (!isAdmin()){
    redirectTo(APP_URL_BASE.'/dashboard');
}