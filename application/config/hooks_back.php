<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'] = array(
                                'class'    => 'Home',
                                'function' => 'check_login',
                                'filename' => 'Home.php',
                                'filepath' => 'hooks'
                                );
$hook['post_controller'] = array(
                                'class'    => 'Home',
                                'function' => 'perfil_correcto',
                                'filename' => 'Home.php',
                                'filepath' => 'hooks'
                                );





//Desde aca empiza el hecho de ser movil o no

//hook para la función navegadores
//POR EL MOMENTO NO ES UTIL ASIQUE LA SACO DE FUNCIONAMIENTO
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Check_nav_disp',
                                'function' => 'navegadores',
                                'filename' => 'Check_nav_disp.php',
                                'filepath' => 'hooks'
                                );


//hook para la función dispositivos
//SI ES IMPORTANTE Y ES QUIEN DICE SI ES MOVIL O NO

$hook['post_controller_constructor'][] = array(
                                'class'    => 'Check_nav_disp',
                                'function' => 'dispositivos',
                                'filename' => 'Check_nav_disp.php',
                                'filepath' => 'hooks'
                                );



//hook para la función robot
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Check_nav_disp',
                                'function' => 'robot',
                                'filename' => 'Check_nav_disp.php',
                                'filepath' => 'hooks'
                                );
        
//hook para la función plataforma   
//ESTA FUNCION NO ES IMPORTNATE DE MOMENTO ASIQ SE ANULA TAMBIEN
/*                    
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Check_nav_disp',
                                'function' => 'plataforma',
                                'filename' => 'Check_nav_disp.php',
                                'filepath' => 'hooks'
                                );
*/


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

