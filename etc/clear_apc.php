<?php
/****
 * I typically create a seperate vhost that listens on localhost, that serves up this script
 * APC needs to be cleared in the shared memory space as your other scripts - so running this
 * from the command line won't cut it.
 */
if( isset($_GET['apcpw']) && $_GET['apcpw'] == 'YourSecretPwHere'){
//We have to clear the APC cache this way, cuz cant do it via command line.     
        echo "Starting APC CLEAR: ".date(DATE_RFC822)."\n";
        set_time_limit(0);
        apc_clear_cache();
//         apc_clear_cache('user');  If you have lots of user entries, may want to keep this commented out
        apc_clear_cache('opcode');      
        echo "APC DONE: ".date(DATE_RFC822)."\n";
}