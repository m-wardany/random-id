<?php
return [

    'encryption_key' => 'kjjkdbhskjhdbmblwiubepfbldjhfbvxjhbkftkuryhflybvljh',
    /**
     * Enable or disable hashing attributes globally after insert 
     */
    'allow_hashing_after_insert' => true,

    /**
     * Enable or disable hashing attributes globally after updating
     */
    'allow_hashing_after_update' => true,

    /**
     * A pattern to be used for the non given attributes
     */
    'hashed_attributed_pattern' => '%_hashed',

    /**
     * To allow Queue for Hashing globally
     */
    'queue' => [
        /**
         * enable or disable async connection
         */
        'enabled' => false,

        /**
         * the name of the listener's queue connection, null for default
         */
        'connection' => null,

        /**
         * the name of the listener's queue, null for default
         */
        'queue' => null,
    ],
];
