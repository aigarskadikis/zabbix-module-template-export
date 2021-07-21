<?php

namespace Modules\TemplateExport;

// HTML wrappers used in current module, there are a lot more of other HTML wrappers
use CButton;
use CTag;
use CScriptTag;
use CScriptTemplate;
use CButtonExport;
use CSubmitButton;

// internal classes; CUrl for easy url generation
use CUrl;

// required for module definition
use Core\CModule as CModule;
use CController as CAction;


class Module extends CModule {

	protected $scripts = [];

	public function init(): void {
	}

	 /**
	 * Before action event handler.
	 *
	 * @param CAction $action    Current request handler object.
	 */
	 
	// will check is the current user action equal to templates.php
	// every enabled module Module.php class methods: onBeforeAction will be called before user requested action is processed.
	// every http anchor tag is "user requested action" if it points to PHP file. and if this .php file is mentioned in Router.php
	public function onBeforeAction(CAction $action): void {
		if ($action->getAction() === 'templates.php') {
			$this->setPostInjectJavascript($_REQUEST);
		}
	}

	/**
	 * For login/logout actions update user seession state in multiple databases.
	 */
	// onTerminate will be called after user request is completed and data is ready to be send back to browser
	public function onTerminate(CAction $action): void {
		echo implode('', $this->scripts);
	}

	protected function setPostInjectJavascript(array $params): void {
		$params += [
			'form' => '',
			'templateid' => 0,
		];

		if (!$params['templateid'] || $params['form'] !== 'update') {
			return;
		}

		if (version_compare(ZABBIX_VERSION, '5.2', '<')) {
			$this->scripts = [
				(new CTag('script', true, [
					(new CSubmitButton(_('Export'), 'export-5-0'))
				]))
					->setId('export-button')
					->setAttribute('type', 'text/x-jquery-tmpl'),
				new CScriptTag(file_get_contents(__DIR__.'/public/template.export.5.0.js'))
			];
		}
		else {
			$this->scripts = [
				(new CScriptTemplate('export-button'))->addItem(
					(new CButtonExport('export.templates', (new CUrl('templates.php'))->getUrl()))
				),
				new CScriptTag(file_get_contents(__DIR__.'/public/template.export.js'))
			];
		}
	}
}