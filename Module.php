<?php

namespace Modules\TemplateExport;

use CButton;
use Core\CModule as CModule;
use CController as CAction;
use CTag;
use CUrl;
use CScriptTag;
use CScriptTemplate;
use CButtonExport;
use CSubmitButton;

class Module extends CModule {

	protected $scripts = [];

	public function init(): void {
	}

	 /**
	 * Before action event handler.
	 *
	 * @param CAction $action    Current request handler object.
	 */
	public function onBeforeAction(CAction $action): void {
		if ($action->getAction() === 'templates.php') {
			$this->setPostInjectJavascript($_REQUEST);
		}
	}

	/**
	 * For login/logout actions update user seession state in multiple databases.
	 */
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