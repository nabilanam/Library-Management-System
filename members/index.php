<?php

if (!isAdmin()){
    redirectTo(APP_BASE_URL.'/dashboard');
}