<?php

/**
 * @var $container \Symfony\Component\DependencyInjection\ContainerBuilder
 */

$container->loadFromExtension('framework', array(
    'test' => null,
    'session' => [
        'storage_id' => 'session.storage.filesystem'
    ],
));
