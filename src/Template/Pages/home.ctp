<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
</head>
<body class="home">
    <div id="content">
        <div class="row">
            <?php Debugger::checkSecurityKeys(); ?>
            <div id="url-rewriting-warning" class="columns large-12 url-rewriting checks">
                <p class="problem">URL rewriting is not properly configured on your server.</p>
                <p>
                    1) <a target="_blank" href="http://book.cakephp.org/3.0/en/installation.html#url-rewriting">Help me configure it</a>
                </p>
                <p>
                    2) <a target="_blank" href="http://book.cakephp.org/3.0/en/development/configuration.html#general-configuration">I don't / can't use URL rewriting</a>
                </p>
            </div>
            <div class="columns large-5 platform checks">
                <?php if (version_compare(PHP_VERSION, '5.4.16', '>=')): ?>
                    <p class="success">Your version of PHP is 5.4.16 or higher.</p>
                <?php else: ?>
                    <p class="problem">Your version of PHP is too low. You need PHP 5.4.16 or higher to use CakePHP.</p>
                <?php endif; ?>

                <?php if (extension_loaded('mbstring')): ?>
                    <p class="success">Your version of PHP has the mbstring extension loaded.</p>
                <?php else: ?>
                    <p class="problem">Your version of PHP does NOT have the mbstring extension loaded.</p>;
                <?php endif; ?>

                <?php if (extension_loaded('openssl')): ?>
                    <p class="success">Your version of PHP has the openssl extension loaded.</p>
                <?php elseif (extension_loaded('mcrypt')): ?>
                    <p class="success">Your version of PHP has the mcrypt extension loaded.</p>
                <?php else: ?>
                    <p class="problem">Your version of PHP does NOT have the openssl or mcrypt extension loaded.</p>
                <?php endif; ?>

                <?php if (extension_loaded('intl')): ?>
                    <p class="success">Your version of PHP has the intl extension loaded.</p>
                <?php else: ?>
                    <p class="problem">Your version of PHP does NOT have the intl extension loaded.</p>
                <?php endif; ?>
            </div>
            <div class="columns large-6 filesystem checks">
                <?php if (is_writable(TMP)): ?>
                    <p class="success">Your tmp directory is writable.</p>
                <?php else: ?>
                    <p class="problem">Your tmp directory is NOT writable.</p>
                <?php endif; ?>

                <?php if (is_writable(LOGS)): ?>
                    <p class="success">Your logs directory is writable.</p>
                <?php else: ?>
                    <p class="problem">Your logs directory is NOT writable.</p>
                <?php endif; ?>

                <?php $settings = Cache::config('_cake_core_'); ?>
                <?php if (!empty($settings)): ?>
                    <p class="success">The <em><?= $settings['className'] ?>Engine</em> is being used for core caching. To change the config edit config/app.php</p>
                <?php else: ?>
                    <p class="problem">Your cache is NOT working. Please check the settings in config/app.php</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="columns large-12 database checks">
                <p class="success">Server IP is <strong><?= $_SERVER['SERVER_ADDR']; ?></strong> (<?= $_SERVER['SERVER_SOFTWARE']; ?> on <strong><?= $_SERVER['SERVER_NAME']; ?></strong>)</p>
                <p class="success">Current session id is <strong><?= session_id(); ?></strong> using the <strong><?= session_module_name(); ?></strong> session module.</p>
                <p class="success">This is the <strong><?= $visits; ?></strong>th visit this session.</p>
                <p class="success">You are <strong><?= $_SERVER['REMOTE_USER']; ?></strong>.</p>
            </div>
        </div>
        <div class="row">
            <div class="columns large-12 database checks">
                <?php
                    try {
                        $connection = ConnectionManager::get('default');
                        $connected = $connection->connect();
                    } catch (Exception $connectionError) {
                        $connected = false;
                        $errorMsg = $connectionError->getMessage();
                        if (method_exists($connectionError, 'getAttributes')):
                            $attributes = $connectionError->getAttributes();
                            if (isset($errorMsg['message'])):
                                $errorMsg .= '<br />' . $attributes['message'];
                            endif;
                        endif;
                    }
                ?>
                <?php if ($connected): ?>
                    <p class="success">CakePHP is able to connect to the database.</p>
                <?php else: ?>
                    <p class="problem">CakePHP is NOT able to connect to the database.<br /><br /><?= $errorMsg ?></p>
                <?php endif; ?>
            </div>
        </div>

        
    </div>

</body>
</html>
