<?php

Yii::import('zii.widgets.CPortlet');

class UsersMenu extends CPortlet
{
	public function init()
	{
		$this->title='Users';
		parent::init();
	}

	protected function renderContent()
	{
		$this->render('users');
	}
}