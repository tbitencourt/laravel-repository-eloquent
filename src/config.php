<?php

/**
 * File config.php
 * PHP version 7
 * @category PHP
 * @package  LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */

declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [

    /*
     * The default configurations to be used by the meta generator.
     */

    'defaults' => [

        /*
         * The default configurations to be used by the Factory Class.
         */

        'factory' => [
            'path'       => 'App\\Repositories',
            'group_path' => true,
        ],
    ],
];
