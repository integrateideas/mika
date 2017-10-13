<?php
use Cake\Core\Configure;
//If a menu has children, then the link for the menu must always be #
//All links must be in the form of ['controller' => 'ControllerName', 'action' =>'action name' ]
return [ 'Menu' => 
                  [
                      
                   'Admin' =>  [
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
                            'Experts' => [
                              'link' => '#',
                              'children' => [
                                    'View Experts' => [
                                        'link' => [
                                              'controller' => 'Experts',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Expert' => [
                                        'link' => [
                                                   'controller' => 'Experts',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Specializations' => [
                              'link' => '#',
                              'children' => [
                                    'View Specializations' => [
                                        'link' => [
                                              'controller' => 'Specializations',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Specialization' => [
                                        'link' => [
                                                   'controller' => 'Specializations',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Specialization Services' => [
                              'link' => '#',
                              'children' => [
                                    'View Specialization Services' => [
                                        'link' => [
                                              'controller' => 'SpecializationServices',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Specialization Service' => [
                                        'link' => [
                                                   'controller' => 'SpecializationServices',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                        ],
                ]
        ];
?>