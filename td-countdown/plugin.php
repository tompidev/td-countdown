<?php

/**
 *  @project        :  BLUDIT 3 - Countdown plugin
 *  @file           :  plugin.php
 *  @user           :  Tompidev
 *  @website        :  https://tompidev.com/
 *  @github         :  https://github.com/tompidev
 *  @email          :  support@tompidev.com
 *
 *  @last-modified  :  2021-Dec-15 17:58:02 CET
 *  @release        :  1.0.0.2150
 *  @licence        :  MIT
 */

class pluginCountdown extends Plugin
{

	public function init()
	{
		$this->dbFields = array(
			'date' 									=> '2021/12/24 20:00',
			'countdownOnHome' 			=> 'off',
			'showOnSidebar' 				=> "on",
			'countdownPosition' 		=> 'after',
			'countdownOnPages' 			=> 'all',
			'selectedPage' 					=> '',
			'countdownAlign'				=> 'center',
			'countdownHeading' 			=> 'There are',
			'countdownHeadingSize' 	=> '3',
			'countdownMessage' 			=> '<h3>until Christmas...</h3>
<hr>
<h1>Test message Heading 1</h1>
<p>This is a normal text in a paragraph</p>'
		);
	}

	public function form()
	{
		global $L;

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

		/*
		* Display settings
		*/
		$html .= '<h4 class="pb-2 pt-3 border-bottom text-primary"><i class="fa fa-desktop mr-2"></i>' . $L->get('displaying-settings-header') . '</h4>';
		/*
		* Displaying on Sidebar
		*
		* The script that checks for the existence of the sidebar.php file is below. (search for $themeDir)
		* If the file does't exists, than this option will be hidden
		*/
		$html .= '<div id="sidebarSwitch" class="d-none">';
		$html .= '<label>' . $L->get('sidebar-label') . '</label>' . PHP_EOL;
		$html .= '<input id="showOnSidebar" name="showOnSidebar" type="radio" value="on" class="mr-1"' . ($this->getValue('showOnSidebar') == "on" ? 'checked' : '') . '>' . $L->get('sidebar-show') . PHP_EOL;
		$html .= '<input id="showOnSidebar" name="showOnSidebar" type="radio" value="off" class="ml-3 mr-1"' . ($this->getValue('showOnSidebar') == "off" ? 'checked' : '') . '>' . $L->get('sidebar-hide') . PHP_EOL;
		$html .= '<div>' . PHP_EOL;
		$html .= '<small class="text-muted">' . $L->g('sidebar-hint') . '</small>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		/*
		* Display Countdown on this page
		*/
		$html .= '<div>';
		$html .= '<label>' . $L->get('show-on-pages-label') . '</label>' . PHP_EOL;
		$html .= '<select id="countdownOnPages" name="countdownOnPages" class="col-lg-2">' . PHP_EOL;
		$html .= '<option value="all" ' . ($this->getValue('countdownOnPages') === 'all' ? 'selected' : '') . '>' . $L->g('show-on-pages-all') . '</option>' . PHP_EOL;
		$html .= '<option value="selectedpage" ' . ($this->getValue('countdownOnPages') === 'selectedpage' ? 'selected' : '') . '>' . $L->g('show-on-pages-selected') . '</option>' . PHP_EOL;
		$html .= '<option value="none" ' . ($this->getValue('countdownOnPages') === 'none' ? 'selected' : '') . '>' . $L->g('show-on-pages-none') . '</option>' . PHP_EOL;
		$html .= '</select>' . PHP_EOL;
		$html .= '<small class="text-muted">' . $L->g('show-on-pages-hint') . '</small>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		/*
		* Get all pages, sub-pages, static and sub-static pages and create a select dropdown
		*/
		$html .= PHP_EOL . '<div id="selectPage" class="form-group d-none">' . PHP_EOL;
		$html .= '<label>' . $L->get('select-a-page-label') . '</label>' . PHP_EOL;
		$html .= '<select id="selectedPage" name="selectedPage" class="col-lg-2">' . PHP_EOL;
		// Get parent
		$parents = buildParentPages();
		foreach ($parents as $parent) {
			$html .= $parent->title() . ', ';
			$html .= '<option value="' . $parent->key() . '" ' . ($this->getValue('selectedPage') === $parent->key() ? 'selected' : '') . '>' . $parent->title() . '</option>' . PHP_EOL;

			if ($parent->hasChildren()) {
				// Get children
				$children = $parent->children();
				foreach ($children as $child) {
					$html .= $child->title() . ', ';
					$html .= '<option value="' . $child->key() . '" ' . ($this->getValue('selectedPage') === $child->key() ? 'selected' : '') . '>' . $child->title() . '</option>' . PHP_EOL;
				}
			}
		}
		// Get static
		$staticPages = buildStaticPages();
		foreach ($staticPages as $static) {
			$html .= $static->title() . ', ';
			$html .= '<option value="' . $static->key() . '" ' . ($this->getValue('selectedPage') === $static->key() ? 'selected' : '') . '>' . $static->title() . '</option>' . PHP_EOL;
		}
		$html .= '</select>' . PHP_EOL;
		$html .= '<small class="text-muted">' . $L->g('select-a-page-hint') . '</small>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		/*
		* Position on a page
		*/
		$html .= '<div id="countdownposition" class="d-none">';
		$html .= '<label>' . $L->get('position-in-content-label') . '</label>' . PHP_EOL;
		$html .= '<select id="countdownPosition" name="countdownPosition" class="col-lg-2">' . PHP_EOL;
		$html .= '<option value="before" ' . ($this->getValue('countdownPosition') === 'before' ? 'selected' : '') . '>' . $L->g('position-in-content-before') . '</option>' . PHP_EOL;
		$html .= '<option value="after" ' . ($this->getValue('countdownPosition') === 'after' ? 'selected' : '') . '>' . $L->g('position-in-content-after') . '</option>' . PHP_EOL;
		$html .= '<option value="both" ' . ($this->getValue('countdownPosition') === 'both' ? 'selected' : '') . '>' . $L->g('position-in-content-both') . '</option>' . PHP_EOL;
		$html .= '</select>' . PHP_EOL;
		$html .= '<small class="text-muted">' . $L->g('position-in-content-hint') . '</small>' . PHP_EOL;


		/*
		* Text settings
		*/
		$html .= '<h4 class="pb-2 pt-4 border-bottom text-primary"><i class="fa fa-file-text mr-2"></i>' . $L->get('text-settings-header') . '</h4>';
		/*
		* Countdown text content alignment settings
		*/
		$html .= '<div>' . PHP_EOL;
		$html .= '<label>' . $L->get('content-alignment-label') . '</label>' . PHP_EOL;
		$html .= '<select id="countdownAlign" name="countdownAlign" class="col-lg-2">' . PHP_EOL;
		PHP_EOL;
		$html .= '<option value="left" ' . ($this->getValue('countdownAlign') === 'left' ? 'selected' : '') . '>' . $L->g('content-alignment-left') . '</option>' . PHP_EOL;
		$html .= '<option value="center" ' . ($this->getValue('countdownAlign') === 'center' ? 'selected' : '') . '>' . $L->g('content-alignment-center') . '</option>' . PHP_EOL;
		$html .= '<option value="right" ' . ($this->getValue('countdownAlign') === 'right' ? 'selected' : '') . '>' . $L->g('content-alignment-right') . '</option>' . PHP_EOL;
		$html .= '</select>' . PHP_EOL;
		$html .= '<span class="tip">' . $L->get('content-alignment-hint') . '</span>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		/*
		* Heading text size options
		*/
		$html .= '<div>' . PHP_EOL;
		$html .= '<label>' . $L->get('heading-text-size-label') . '</label>' . PHP_EOL;
		$html .= '<select id="countdownHeadingSize" name="countdownHeadingSize" class="col-lg-2">' . PHP_EOL;
		PHP_EOL;
		$html .= '<option value="1" ' . ($this->getValue('countdownHeadingSize') === '1' ? 'selected' : '') . '>' . $L->g('heading-text-size-default') . '</option>' . PHP_EOL;
		$html .= '<option value="2" ' . ($this->getValue('countdownHeadingSize') === '2' ? 'selected' : '') . '>' . $L->g('heading-text-size-medium') . '</option>' . PHP_EOL;
		$html .= '<option value="3" ' . ($this->getValue('countdownHeadingSize') === '3' ? 'selected' : '') . '>' . $L->g('heading-text-size-big') . '</option>' . PHP_EOL;
		$html .= '<option value="4" ' . ($this->getValue('countdownHeadingSize') === '4' ? 'selected' : '') . '>' . $L->g('heading-text-size-large') . '</option>' . PHP_EOL;
		$html .= '</select>' . PHP_EOL;
		$html .= '<span class="tip">' . $L->get('heading-text-size-hint') . '</span>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		/*
		* Unique text imputs
		*/
		$html .= '<div>' . PHP_EOL;
		$html .= '<label>' . $L->get('heding-text-label') . '</label>' . PHP_EOL;
		$html .= '<input id="countdownHeading" name="countdownHeading" type="text" class="form-control col-lg-6" value="' . $this->getValue('countdownHeading') . '">' . PHP_EOL;
		$html .= '<span class="tip">' . $L->get('heding-text-hint') . '</span>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;

		$html .= '<div>' . PHP_EOL;
		$html .= '<label>' . $L->get('text-message-label') . '</label>' . PHP_EOL;
		$html .= '<textarea id="countdownMessage" name="countdownMessage" type="textarea" class="form-control col-lg-6" rows="3">' . $this->getValue('countdownMessage') . '</textarea>' . PHP_EOL;
		$html .= '<span class="tip">' . $L->get('text-message-hint') . '</span>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;

		/*
		* Date / Time settings
		*/
		$html .= '<h4 class="pb-2 pt-4 border-bottom text-primary"><i class="fa fa-calendar mr-2"></i>' . $L->get('date-time-settings-header') . '</h4>';
		/*
		* Date/time picker
		*/
		$html .= '<div>' . PHP_EOL;
		$html .= '<label>' . $L->get('date-time-settings-date') . '</label>' . PHP_EOL;
		$html .= '<input id="countdownDatePicker" name="date" type="text" class="form-control col-lg-2" placeholder="24/12/2032" value="' . $this->getValue('date') . '" readonly>' . PHP_EOL;
		$html .= '<span class="tip">' . $L->get('date-time-settings-hint') . '</span>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;

		/*
		* App footer for plugin version and developer's urls
		*/
		$html .= PHP_EOL . '<div class="text-center pt-3 mt-4 border-top text-muted">' . PHP_EOL;
		$html .= $this->name() . ' - v<span id="bdaPluginThisVersion">' . $this->version() . '</span> @ ' . date('Y') . ' by ' .  $this->author() . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		$html .= '<div class="text-center">' . PHP_EOL;
		$html .= '<a class="fa fa-2x fa-globe" href="https://www.tompidev.com/" target="_blank" title="Visit TompiDev\'s Website"></a>' . PHP_EOL;
		$html .= '<a class="fa fa-2x fa-github" href="https://www.github.com/tompidev" target="_blank" title="Visit TompiDev on Github"></a>' . PHP_EOL;
		$html .= '<a class="fa fa-2x fa-twitter" href="https://www.twitter.com/tompidev" target="_blank" title="Visit TompiDev on Twitter"></a>' . PHP_EOL;
		$html .= '<a class="fa fa-2x fa-envelope" href="mailto:support@tompidev.com/?subject=Question%20about%20' . $this->name() . '" title="Send me an email"></a>' . PHP_EOL;
		$html .= '<a class="fa fa-2x fa-cubes" href="https://www.tompidev.com/booty-dark-admin-plugin" target="_blank" title="Plugin\'s website on tompidev.com"></a>' . PHP_EOL;


		$html .= '</div>' . PHP_EOL;

		return $html;
	}

	public function adminBodyEnd()
	{
		/*
		* Check if sidebar.php file exists in Theme directory.
		* If NOT, than hide the displaying on sidebar option
		*/
		$themeDir = THEME_DIR_PHP;
		foreach (glob($themeDir . 'sidebar.php') as $file) {
			if (file_exists($file)) {
				echo "<script>$('#sidebarSwitch').removeClass('d-none')</script>";
			}
		}

		$script  = '<script>' . PHP_EOL;
		$script .= '$("document").ready(function () {' . PHP_EOL;

		/*
		 * Displaying countdownOnPages select dropdown if 'selected page' is selected
		 */
		if ($this->getValue('countdownOnPages') == "selectedpage") {
			$script .= '$("#selectPage").removeClass("d-none");' . PHP_EOL;
		}

		/*
		 * Displaying countdownOnPages select dropdown if 'selected page' is selected
		 */
		if ($this->getValue('countdownOnPages') !== "none") {
			$script .= '$("#countdownposition").removeClass("d-none");' . PHP_EOL;
		}
		$script .= '$("#countdownOnPages").change(function() {' . PHP_EOL;
		$script .= 'if($(this).val() == "none"){' . PHP_EOL;
		$script .= '$("#countdownposition").addClass("d-none");' . PHP_EOL;
		$script .= '}else{;' . PHP_EOL;
		$script .= '$("#countdownposition").removeClass("d-none");' . PHP_EOL;
		$script .= '}' . PHP_EOL;
		$script .= 'if($(this).val() == "selectedpage"){' . PHP_EOL;
		$script .= '$("#selectPage").removeClass("d-none");' . PHP_EOL;
		$script .= '}else{;' . PHP_EOL;
		$script .= '$("#selectPage").addClass("d-none");' . PHP_EOL;
		$script .= '}' . PHP_EOL;
		$script .= '});' . PHP_EOL;

		/*
		 * Initialize Calendar
		 */
		$script .= '$("#countdownDatePicker").datetimepicker({' . PHP_EOL;
		$script .= 'timepicker: true,' . PHP_EOL; // FALSE means hours and minutes are switched off
		$script .= 'format: "Y/m/d H:s"' . PHP_EOL; // if timepicker is true
		$script .= '});' . PHP_EOL;
		$script .= '})' . PHP_EOL;

		$script .= '</script>' . PHP_EOL;

		return $script;
	}

	public function siteHead()
	{
		// if($this->getValue('countdownPosition')){
		return '
		<link rel="stylesheet" type="text/css" href="' . HTML_PATH_CORE_CSS . 'line-awesome/css/line-awesome-font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="' . DOMAIN_PLUGINS . 'countdown/countdown.css">';
		// }
	}

	public function pageBegin()
	{
		global $L;
		global $page;
		global $WHERE_AM_I;

		if ($WHERE_AM_I == "page" && $this->getValue('countdownOnPages') !== "none") {
			if ($this->getValue('countdownPosition') == "before" || $this->getValue('countdownPosition') == "both") {
				if ($this->getValue('countdownOnPages') == "selectedpage") {
					if ($page->key() == $this->getValue('selectedPage')) {
						include('counter.php');
					}
				}else{
						include('counter.php');
			}
			}
		}
	}

	public function pageEnd()
	{
		global $L;
		global $page;
		global $WHERE_AM_I;

		if ($WHERE_AM_I == "page" && $this->getValue('countdownOnPages') !== "none") {
			if ($this->getValue('countdownPosition') == "after" || $this->getValue('countdownPosition') == "both") {
				if ($this->getValue('countdownOnPages') == "selectedpage") {
					if ($page->key() == $this->getValue('selectedPage')) {
						include('counter.php');
					}
				}else{
						include('counter.php');
				}
			}
		}
	}

	public function siteSidebar()
	{
		global $L;
		global $WHERE_AM_I;

		/*
		* If the 'showOnSidebar' option value is 'on', than insert the the counter-sidebar.php file
		*/
			if ($this->getValue('showOnSidebar') == "on") {
				include('counter-sidebar.php');
			}
	}

	public function siteBodyEnd()
	{
		$jquery = Theme::jquery();
		$jquery .= '<script src="' . DOMAIN_PLUGINS . 'countdown/jquery.countdown.min.js"></script>';
		return $jquery;
	}
}
