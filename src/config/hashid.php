<?php
return [
    /**
     * Key to be used for encrypting keys, if null, APP_KEY will be used
     */
    'encryption_key' => null,

    /**
     * Minimum length to be used for the encrypted key
     */
    'min_length' => 5,

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
    'hashed_attributed_pattern' => '%s_hashed',

    /**
     * Charachters to be used in encryption for Mixed encryption  
     */
    'mix_alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',

    /**
     * Charachters to be used in encryption for Text encryption  
     */
    'text_alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

    /**
     * Charachters to be used in encryption for Int encryption  
     */
    'int_alphabet' => '0123456789',

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
