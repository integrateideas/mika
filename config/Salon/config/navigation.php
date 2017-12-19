<?php
use Cake\Core\Salon\Configure;
//If a menu has children, then the link for the menu must always be #
//All links must be in the form of ['controller' => 'ControllerName', 'action' =>'action name' ]
return [ 'Menu' => 
                  [
                      
                   'Users' =>  [
                            'Users' => [
                              'link' => '#',
                              'children' => [
                                    'View Users' => [
                                        'link' => [
                                              'controller' => 'Users',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add User' => [
                                        'link' => [
                                                   'controller' => 'Users',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'User Salons' => [
                              'link' => '#',
                              'children' => [
                                    'View User Salons' => [
                                        'link' => [
                                              'controller' => 'UserSalons',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add User Salons' => [
                                        'link' => [
                                                   'controller' => 'User Salon',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Add Card' => [
                              'link' => '/salon/userCards/add', 
                            ],
                            'Account Details' => [
                              'link' => '#',
                              'children' => [
                                    'View Account Details' => [
                                        'link' => [
                                              'controller' => 'AccountDetails',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Account Detail' => [
                                        'link' => [
                                                   'controller' => 'AccountDetails',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                        ],
                ]
        ];
?>