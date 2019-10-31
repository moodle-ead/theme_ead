<?php
/**
 * Override core renderer
 * 
 * PAGE LAYOUTS: 
 *  mypage
 *  cbase
 *  course
 *  coursecategory
 *  embedded
 *  frametop
 *  frontpage
 *  incourse
 *  login
 *  maintenance
 *  mydashboard
 *  mypublic
 *  popup
 *  print
 *  redirect
 *  type
 *  report
 *  secure
 *  standard
 * 
 * PAGE TYPES:
 *  admin-{$PAGE->pagetype}
 *  admin-auth-{$auth}
 *  admin-portfolio-{$plugin}
 *  admin-repository-{$plugin}
 *  admin-repository-{$repository}
 *  admin-setting-{$category}
 *  admin-setting-{$section}
 *  bogus-page
 *  course-index-category
 *  course-view
 *  course-view-{$course->format}
 *  maintenance-message
 *  mod-{$cm->modname}-delete
 *  mod-$mod-view
 *  mod-assign-{$action}
 *  mod-data-field-{$newtype}
 *  mod-forum-view 
 *  mod-lesson-view 
 *    branchtable
 *    cluster
 *    endofbranch
 *    endofcluster
 *    essay
 *    matching
 *    multichoice
 *    numerical
 *    shortanswer
 *    truefalse
 *  mod-quiz-edit
 *  my-index 
 *  page-type
 *  question-type{$question->qtype}
 *  site-index 
 *  user-files 
 *  user-preferences
 *  user-profile 
 * 
 * @category  MoodleTheme
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
namespace theme_ead\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;

defined('MOODLE_INTERNAL') || die;


/**
 * Extending the core_renderer interface.
 * 
 * lib/outputrenderers.php --> core_renderer
 * 
 * @category  Renderer
 * @package   Theme_Ead.Classes.Output
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @copyright 2017 IFRN
 * @license   MIT https://opensource.org/licenses/MIT    
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**  
     * Override header
     *
     * @return string HTML of header
     */
    public function header() {
        return parent::header();
    }

    public function navbar() {
        $result = parent::navbar();
        $result = str_replace(">Painel<", '>Salas de aula<', $result);
        // $result = str_replace('<li class="breadcrumb-item">Meus cursos</li>', '', $result);
        
        return $result;
    }

    /**
     * Allow plugins to provide some content to be rendered in the navbar.
     *
     * @see message_popup_render_navbar_output in lib.php
     * 
     * @return string HTML for the navbar
     */
    // public function navbar_plugin_output() {

    //     $output = '';
    //     // // Early bail out conditions.
    //     // if (!isloggedin() || isguestuser() || user_not_fully_set_up($USER) || 
    //     //     get_user_preferences('auth_forcepasswordchange') || 
    //     //     ($CFG->sitepolicy && !$USER->policyagreed && !is_siteadmin())) {
    //     //     return $output;
    //     // }
    //     $output .= $this->header_messsage();
    //     // $output .= $this->header_help();
    //     $output .= $this->header_notification();
    //     $output .= $this->header_admin();
    //     // $output .= $this->header_pagetitle();
    //     return $output;
    // }

    public function navbar_pagetitle_output() {
        $output = '';
        $output .= $this->header_pagetitle();
        return $output;
    }
        

    // /**  
    //  * Override to add additional class for the random login image to the body.
    //  *
    //  * @param string|array $additionalclasses Any additional classes to give the body tag,
    //  * 
    //  * @return string HTML attributes to use within the body tag. This includes an ID and classes.
    //  */
    // public function body_attributes($additionalclasses = array()) {
    //     global $PAGE, $CFG;
    //     require_once($CFG->dirroot . '/theme/ead/locallib.php');

    //     if (!is_array($additionalclasses)) {
    //         $additionalclasses = explode(' ', $additionalclasses);
    //     }

    //     // MODIFICATION START.
    //     // Only add classes for the login page.
    //     // if ($PAGE->bodyid == 'page-login-index') {
    //     //     $additionalclasses[] = 'loginbackgroundimage';
    //     //     // Generating a random class for displaying a random image for the login page.
    //     //     $additionalclasses[] = theme_ead_get_random_loginbackgroundimage_class();
    //     // }
    //     // MODIFICATION END.

    //     return ' id="'. $this->body_id().'" class="'.$this->body_css_classes($additionalclasses).'"';
    // }

    // /**
    //  * Override to be able to use uploaded images from admin_setting as well.
    //  *
    //  * @return string The favicon URL
    //  */
    // public function favicon() 
    // {
    //     global $PAGE;
    //     // MODIFICATION START.
    //     if (!empty($PAGE->theme->settings->favicon)) {
    //         return $PAGE->theme->setting_file_url('favicon', 'favicon');
    //     } else {
    //         return $this->pix_url('favicon', 'theme');
    //     }
    //     // MODIFICATION END.
    //     /* ORIGINAL START.
    //     return $this->pix_url('favicon', 'theme');
    //     ORIGINAL END. */
    // }

    public function user_menu($user = null, $withlinks = null, $usermenuclasses=null) {
        global $PAGE;
        if ( ($PAGE->pagelayout == "frontpage") && (!isloggedin()))  {
             return '<div class="login"><a class="login_ead" href="'. (new moodle_url('/login/index.php'))->out() .'">Entrar</a></div>';
        }else{
            return html_writer::div(parent::user_menu($user, $withlinks), $usermenuclasses);
        }
    }

    public function full_header() {
        global $PAGE;
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        return $this->render_from_template('core/full_header', $header);
    }

    public function page_heading_button() {
        $btn = $this->page->button;
        //<i class="icon fa fa-plus"></i>
        $icon = '<i class="icon fa %s" style="font-size: 24px; margin: 0 4px 0 0;" title="%s"></i>';
        if (!empty($btn)) {
            $btn = str_replace(get_string('blocksediton'), sprintf($icon, 'fa-pencil-square-o', get_string('blocksediton')), $btn);
            $btn = str_replace(get_string('blockseditoff'), sprintf($icon, 'fa-pencil-square', get_string('blockseditoff')), $btn);
            $btn = str_replace(get_string('updatemymoodleon'), sprintf($icon, 'fa-pencil-square-o', get_string('updatemymoodleon')), $btn);
            $btn = str_replace(get_string('updatemymoodleoff'), sprintf($icon, 'fa-pencil-square', get_string('updatemymoodleoff')), $btn);
            $btn = str_replace(get_string('resetpage', 'my'), sprintf($icon, 'fa-retweet', get_string('resetpage', 'my')), $btn);
            $btn = str_replace(get_string('reseteveryonesdashboard', 'my'), sprintf($icon, 'fa-retweet', get_string('reseteveryonesdashboard', 'my')), $btn);
            $btn = str_replace(get_string('reseteveryonesprofile', 'my'), sprintf($icon, 'fa-retweet', get_string('reseteveryonesprofile', 'my')), $btn);
            // $btn = str_replace(, $icon, $btn);
        }
        return $btn;
    }

    // /**
    //  * Override to display course settings on every course site for permanent access
    //  *
    //  * This is an optional menu that can be added to a layout by a theme. It contains the
    //  * menu for the course administration, only on the course main page.
    //  *
    //  * @return string
    //  */
    // public function context_header_settings_menu() {
    //     $context = $this->page->context;
    //     $menu = new action_menu();

    //     $items = $this->page->navbar->get_items();
    //     $currentnode = end($items);

    //     $showcoursemenu = false;
    //     $showfrontpagemenu = false;
    //     $showusermenu = false;

    //     // MODIFICATION START.
    //     // REASON: With the original code, the course settings icon will only appear on the course main page.
    //     // Therefore the access to the course settings and related functions is not possible on other
    //     // course pages as there is no omnipresent block anymore. We want these to be accessible
    //     // on each course page.
    //     if (($context->contextlevel == CONTEXT_COURSE || $context->contextlevel == CONTEXT_MODULE) && !empty($currentnode)) {
    //         $showcoursemenu = true;
    //     }
    //     // MODIFICATION END.
    //     // @codingStandardsIgnoreStart
    //     /* ORIGINAL START.
    //     if (($context->contextlevel == CONTEXT_COURSE) &&
    //             !empty($currentnode) &&
    //             ($currentnode->type == navigation_node::TYPE_COURSE || $currentnode->type == navigation_node::TYPE_SECTION)) {
    //         $showcoursemenu = true;
    //     }
    //     ORIGINAL END. */
    //     // @codingStandardsIgnoreEnd

    //     $courseformat = course_get_format($this->page->course);
    //     // This is a single activity course format, always show the course menu on the activity main page.
    //     if ($context->contextlevel == CONTEXT_MODULE && !$courseformat->has_view_page()) {

    //         $this->page->navigation->initialise();
    //         $activenode = $this->page->navigation->find_active_node();
    //         // If the settings menu has been forced then show the menu.
    //         if ($this->page->is_settings_menu_forced()) {
    //             $showcoursemenu = true;
    //         } else if (!empty($activenode) && ($activenode->type == navigation_node::TYPE_ACTIVITY ||
    //             $activenode->type == navigation_node::TYPE_RESOURCE)) {

    //             // We only want to show the menu on the first page of the activity. This means
    //             // the breadcrumb has no additional nodes.
    //             if ($currentnode && ($currentnode->key == $activenode->key && $currentnode->type == $activenode->type)) {
    //                 $showcoursemenu = true;
    //             }
    //         }
    //     }

    //     // This is the site front page.
    //     if ($context->contextlevel == CONTEXT_COURSE && !empty($currentnode) && $currentnode->key === 'home') {
    //         $showfrontpagemenu = true;
    //     }

    //     // This is the user profile page.
    //     if ($context->contextlevel == CONTEXT_USER && !empty($currentnode) && $currentnode->key === 'myprofile') {
    //         $showusermenu = true;
    //     }

    //     if ($showfrontpagemenu) {
    //         $settingsnode = $this->page->settingsnav->find('frontpage', navigation_node::TYPE_SETTING);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

    //             // We only add a list to the full settings menu if we didn't include every node in the short menu.
    //             if ($skipped) {
    //                 $text = get_string('morenavigationlinks');
    //                 $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
    //                 $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
    //                 $menu->add_secondary_action($link);
    //             }
    //         }
    //     } else if ($showcoursemenu) {
    //         $settingsnode = $this->page->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

    //             // We only add a list to the full settings menu if we didn't include every node in the short menu.
    //             if ($skipped) {
    //                 $text = get_string('morenavigationlinks');
    //                 $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
    //                 $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
    //                 $menu->add_secondary_action($link);
    //             }
    //         }
    //     } else if ($showusermenu) {
    //         // Get the course admin node from the settings navigation.
    //         $settingsnode = $this->page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
    //         if ($settingsnode) {
    //             // Build an action menu based on the visible nodes from this navigation tree.
    //             $this->build_action_menu_from_navigation($menu, $settingsnode);
    //         }
    //     }

    //     return $this->render($menu);
    // }

    // /**
    //  * Override to use theme_ead login template renders the login form.
    //  *
    //  * @param \core_auth\output\login $form The renderable.
    //  * 
    //  * @return string
    //  */
    // public function render_login(\core_auth\output\login $form) {
    //     global $SITE;

    //     $context = $form->export_for_template($this);

    //     // Override because rendering is not supported in template yet.
    //     $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
    //     $context->errorformatted = $this->error_text($context->error);
    //     $url = $this->get_logo_url();
    //     if ($url) {
    //         $url = $url->out(false);
    //     }
    //     $context->logourl = $url;
    //     $context->sitename = format_string($SITE->fullname, true,
    //         ['context' => context_course::instance(SITEID), "escape" => false]);
    //     // MODIFICATION START.
    //     // Only if setting "loginform" is checked, then call own login.mustache.
    //     if (get_config('theme_ead', 'loginform') == 'yes') {
    //         return $this->render_from_template('theme_ead/loginform', $context);
    //     } else {
    //         return $this->render_from_template('core/login', $context);
    //     }
    //     // MODIFICATION END.
    //     /* ORIGINAL START.
    //     return $this->render_from_template('core/login', $context);
    //     ORIGINAL END. */
    // }

    // /**
    //  * Take a node in the nav tree and make an action menu out of it.
    //  * The links are injected in the action menu.
    //  *
    //  * @param action_menu $menu
    //  * @param navigation_node $node
    //  * @param boolean $indent
    //  * @param boolean $onlytopleafnodes
    //  * 
    //  * @return boolean nodesskipped - True if nodes were skipped in building the menu
    //  */
    // protected function build_action_menu_from_navigation(
    //     action_menu $menu,
    //     navigation_node $node,
    //     $indent = false,
    //     $onlytopleafnodes = false
    // ) {
    //     $skipped = false;
    //     // Build an action menu based on the visible nodes from this navigation tree.
    //     foreach ($node->children as $menuitem) {
    //         if ($menuitem->display) {
    //             if ($onlytopleafnodes && $menuitem->children->count()) {
    //                 $skipped = true;
    //                 continue;
    //             }
    //             if ($menuitem->action) {
    //                 if ($menuitem->action instanceof action_link) {
    //                     $link = $menuitem->action;
    //                     // Give preference to setting icon over action icon.
    //                     if (!empty($menuitem->icon)) {
    //                         $link->icon = $menuitem->icon;
    //                     }
    //                 } else {
    //                     $link = new action_link($menuitem->action, $menuitem->text, null, null, $menuitem->icon);
    //                 }
    //             } else {
    //                 if ($onlytopleafnodes) {
    //                     $skipped = true;
    //                     continue;
    //                 }
    //                 $link = new action_link(new moodle_url('#'), $menuitem->text, null, ['disabled' => true], $menuitem->icon);
    //             }
    //             if ($indent) {
    //                 $link->add_class('m-l-1');
    //             }
    //             if (!empty($menuitem->classes)) {
    //                 $link->add_class(implode(" ", $menuitem->classes));
    //             }

    //             $menu->add_secondary_action($link);
    //             $skipped = $skipped || $this->build_action_menu_from_navigation($menu, $menuitem, true);
    //         }
    //     }
    //     return $skipped;
    // }

    protected function header_pagetitle() {
        global $PAGE;
        $pagetitle = $PAGE->title;

        if (isloggedin()) {
            if($PAGE->pagelayout == 'frontpage' || $PAGE->pagelayout == 'mydashboard') {
                $pagetitle = "Salas de aula";
            } elseif ($PAGE->pagelayout == 'course' || $PAGE->pagelayout == 'incourse') {
                $pagetitle = "Sala de aula";
            } elseif ($PAGE->pagelayout == 'admin') {
                $pagetitle = "Administração";
            }
        }

        return '<p id="navbar_pagetitle" class="d-none d-sm-none d-md-block">'. $pagetitle .'</p>';
    }

    protected function header_help() {
        return '<div class="popover-region collapsed popover-region-help" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
        <a href="https://moodle.org/mod/forum/view.php?id=50"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                    <i class="icon fa fa-question-circle fa-fw " aria-hidden="true" title="Obter ajuda" aria-label="Obter ajuda"></i>
        </div></a></div>';
    }

    protected function header_admin() {
        if (is_siteadmin()) {
            return '<div class="popover-region collapsed popover-region-admin" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
            <a href="'. (new moodle_url('/admin/search.php'))->out() .'"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                <i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Administração" aria-label="Administração"></i>
            </div></a></div>';
        }
    }

    protected function header_notification() {
        global $USER;
	// Add the notifications popover.
	$output = '';
        $enabled = \core_message\api::is_processor_enabled("popup");
        if ($enabled && isloggedin()) {
            $context = [
                'userid' => $USER->id,
                'urls' => [
                    'seeall' => (new moodle_url('/message/output/popup/notifications.php'))->out(),
                    'preferences' => (new moodle_url('/message/notificationpreferences.php', ['userid' => $USER->id]))->out(),
                ],
            ];
            $output .= $this->render_from_template('message_popup/notification_popover', $context);
        }
        if (is_siteadmin()) {
            return '<div class="popover-region collapsed popover-region-admin" id="nav-help-popover-container" data-userid="2" data-region="popover-region">
            <a href="'. (new moodle_url('/admin/search.php'))->out() .'"><div class="popover-region-toggle nav-link" data-region="popover-region-toggle" aria-role="button" aria-controls="popover-region-container-5a254db9cba625a254db9b2d7016" aria-haspopup="true" aria-label="Mostrar janela de mensagens sem as novas mensagens" tabindex="0">
                <i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Administração" aria-label="Administração"></i>
            </div></a></div>';
        }
    
        return $output;
    }

    protected function header_messsage() {
        global $USER, $CFG;
        $output = "";
        if (!empty($CFG->messaging)) {
            $unreadcount = \core_message\api::count_unread_conversations($USER);
            $context = [
                'userid' => $USER->id,
                'unreadcount' => $unreadcount,
                'urls' => [
                    'seeall' => (new moodle_url('/message/index.php'))->out(),
                    'writeamessage' => (new moodle_url('/message/index.php', ['contactsfirst' => 1]))->out(),
                    'preferences' => (new moodle_url('/message/edit.php', ['id' => $USER->id]))->out(),
                ],
            ];
            $output .= $this->render_from_template('message_popup/message_popover', $context);
        }
        return $output;
    }      
    
}
