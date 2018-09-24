<?php
namespace Cotizador;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\MeasureUnitController::class => Controller\Factory\MeasureUnitControllerFactory::class,
            Controller\BrandController::class => Controller\Factory\BrandControllerFactory::class,
            Controller\ClientController::class => Controller\Factory\ClientControllerFactory::class,
            Controller\SupplierController::class => Controller\Factory\SupplierControllerFactory::class,
            Controller\CategoryController::class => Controller\Factory\CategoryControllerFactory::class,
            Controller\CurrencyController::class => Controller\Factory\CurrencyControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'suppliers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/suppliers[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SupplierController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'clients' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/clients[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\ClientController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'categories' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/categories[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\CategoryController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'currencies' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/currencies[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\CurrencyController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'brands' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/brands[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\BrandController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'measureUnits' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/measure-units[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\MeasureUnitController::class,
                        'action'        => 'index',
                    ],
                ],
            ],

        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\ClientController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                //['actions' => ['index', 'add', 'edit', 'view'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                ['actions' => ['index', 'add', 'edit', 'view'], 'allow' => '+client.manage']
            ],
            Controller\SupplierController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'add', 'edit', 'view'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                //['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '+user.manage']
            ],
            Controller\CategoryController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'add', 'edit', 'view', 'delete'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                //['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '+user.manage']
            ],
            Controller\CurrencyController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'add', 'edit', 'view', 'delete'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                //['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '+user.manage']
            ],
            Controller\BrandController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'add', 'edit', 'view', 'delete'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                //['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '+user.manage']
            ],
            Controller\MeasureUnitController::class => [
                // Give access to "index", "add", "edit", "view", "delete" actions to users having the "measure-unit.manage" permission.
                ['actions' => ['index', 'add', 'edit', 'view', 'delete'], 'allow' => '*'],
            ],

        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\MeasureUnitManager::class => Service\Factory\MeasureUnitManagerFactory::class,
            Service\BrandManager::class => Service\Factory\BrandManagerFactory::class,
            Service\ClientManager::class => Service\Factory\ClientManagerFactory::class,
            Service\SupplierManager::class => Service\Factory\SupplierManagerFactory::class,
            Service\CategoryManager::class => Service\Factory\CategoryManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'cotizador' => __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
