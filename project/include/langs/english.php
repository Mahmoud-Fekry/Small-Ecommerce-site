<?php
    
function lang( $phrase) {
    static $lang = array(
        
        //Dashboard page
        
        //Navbar links
        
        'BRAND' => 'Home',
        'CATEGORIES' => 'Categories',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'COMMENTS' => 'Comments',
        'STATISTICS' => 'Statistics',
        'LOGS' => 'Logs',
        'EDIT' => 'Edit Profile',
        'SETTING' => 'Settings',
        'LOGOUT' => 'Logout',
        '' => ''
        
    );
    return $lang[$phrase];
}
