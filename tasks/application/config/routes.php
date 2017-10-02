<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//CUSTOM ROUTES

//GENERAL ROUTES
$route['about-taskfeed'] = 'open/about_taskfeed';
$route['t-o-s'] = 'open/terms';
$route['privacy-policy'] = 'open/privacy';




//Member routes
$route['dashboard/all-feeds'] = 'dashboard/all_feeds';
$route['dashboard/all-feeds/(:num)'] = 'dashboard/all_feeds';

//New project wizard
$route['project/new/step1'] = 'project/new_project_step1';
$route['project/new/step2'] = 'project/new_project_step2';
$route['project/new/step3'] = 'project/new_project_step3';
$route['project/new/completed'] = 'project/new_project_completed';

//VIEW PROJECTS & DISCUSSIONS
$route['project/view/(:any)/discussion'] = 'project/discussion';
$route['project/view/(:any)/discussion/(:any)'] = 'project/discussion';
$route['project/view/(:any)/todo'] = 'project/todo';
$route['project/view/(:any)/milestones'] = 'project/milestones';


$route['project/view/(:any)'] = 'project/view_project';
$route['project/edit'] = 'project/edit_project';
$route['project/edit/(:any)'] = 'project/edit_project';



// JOINING PROJECT
$route['project/join/error'] = 'project/join_error';
$route['project/join/(:any)'] = 'project/join_project';
$route['project/contractor/new'] = 'project/add_new_contractors';


//Leaving Project
$route['project/leave/(:num)'] = 'project/leave_project';
$route['project/reject/(:num)'] = 'project/reject_project';



//MANAGE PROJECT AND TASKS
$route['project/manage/projects'] = 'project/manage_projects';
$route['project/manage/projects/(:num)'] = 'project/manage_projects';

$route['project/manage/tasks'] = 'project/manage_tasks';
$route['project/manage/tasks/(:num)'] = 'project/manage_tasks';

$route['project/delete/(:num)'] = 'project/delete_project';
$route['project/finish/(:num)'] = 'project/finish_project';

//DELETE TODO
$route['project/delete-todo/(:num)/(:any)'] = 'project/delete_todo';
$route['project/delete-milestone/(:num)/(:any)'] = 'project/delete_milestone';

$route['default_controller'] = "open";
$route['404_override'] = '';



//TEAM WORKS
$route['team/manage'] = 'actions/manage_team';
$route['team/invites'] = 'actions/team_invites';
$route['team/joined'] = 'actions/team_joined';

$route['team/accept-request/(:num)'] = 'actions/team_accept_request';
$route['team/deny-request/(:num)'] = 'actions/team_deny_request';
$route['team/leave-team/(:num)'] = 'actions/leave_team';
$route['team/remove-member/(:num)'] = 'actions/remove_member';

//PRIORITY QUEUE ROUTES
$route['priority/view/(:any)'] = 'priority/view_priority';
$route['priority/new/task'] = 'priority/new_task';
$route['priority/order/save/(:num)'] = 'priority/save_order';
$route['priority/share/new'] = 'priority/share_new';


// POST INSTALLATION SCRIPTS
$route['project/install/discussion/(:any)'] = 'project/install_discussion';
$route['project/install/milestone/(:any)'] = 'project/install_milestone';
$route['project/install/todo/(:any)'] = 'project/install_todo';



//AUTHENTICATION ROUTES
$route['authentication/password-recovery'] = 'authentication/forgot_password';
$route['authentication/reset-password/(:any)'] = 'authentication/reset_password';
$route['authentication/resend-activation/(:any)'] = 'authentication/resend_activation';
$route['activate/account/(:any)'] = 'authentication/activate_account';


//PROFILE ROUTES
$route['user/profile/(:any)'] = 'open/profile';
$route['user/edit/profile'] = 'actions/edit_profile';

/* End of file routes.php */
/* Location: ./application/config/routes.php */