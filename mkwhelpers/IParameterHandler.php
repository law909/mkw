<?php
namespace mkwhelpers;

interface IParameterHandler {

	public function getParam($key,$default=null,$sanitize=true);

	public function getRequestParam($key,$default=null,$sanitize=true);

	public function getBoolParam($key,$default=false);

	public function getNumParam($key,$default=0);

	public function getIntParam($key,$default=0);

	public function getFloatParam($key,$default=0.0);

	public function getStringParam($key,$default='');

	public function getOriginalStringParam($key,$default='');

	public function getDateParam($key,$default='');

	public function getArrayParam($key,$default=array());

	public function getBoolRequestParam($key,$default=false);

	public function getNumRequestParam($key,$default=0);

	public function getIntRequestParam($key,$default=0);

	public function getFloatRequestParam($key,$default=0.0);

	public function getStringRequestParam($key,$default='');

	public function getOriginalStringRequestParam($key,$default='');

	public function getDateRequestParam($key,$default='');

	public function getArrayRequestParam($key,$default=array());

}