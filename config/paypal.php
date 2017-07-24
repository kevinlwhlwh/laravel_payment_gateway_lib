<?php

return [

    /** set your paypal credential **/

    'client_id' =>'AbeFc8mUjL64NjxCUiuGnPS6QV0nKCAiE7T9gQJvPd0xrQWg_5K-ZpaC2pFgmv579yUIuuRDe41g03Sg',

    'secret' => 'EO9wlUf5wg8yiSWJ352_Y7vIJbMvq_kyP_2nlzgUSo1NeHjTzisgY7Zc1rbfl8d94aEUG7zoDSDgeu9Q',

    /** SDK configuration **/

    'settings' => [
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',


        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 200,


        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,


        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',


        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ]
];