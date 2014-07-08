<?php

/**
 * A controller to easily output JSON stuff
 * @todo overwrite all the other render methods to disable all of them. They should not pertain to this type of controller.
 * @todo maybe we could replace $layout=false by overriding getLayoutFile() to always return false
 */
class JsonController extends Controller {

	public $layout = false;

	protected $responseStatusCode = HTTP_OK;

	public function beforeAction($action) {
		header('Content-Type: application/json');
		return true;
	}

	protected function setStatusCode() {
		header(implode(' ', [
			$_SERVER['SERVER_PROTOCOL'],
			$this->responseStatusCode,
			HTTP_MESSAGE($this->responseStatusCode)
		]));
	}

	public function beforeRender($data) {
		return $data;
	}

	public function afterRender($nothing, &$output = null) { }

	/**
	 * Overrides default rendering method by only allowing one argument: the JSON representation.
	 * Echoes the JSON representation of $data and exits.
	 *
	 * @param mixed $data Whatever should be returned as JSON
	 * @param null   $forget1 Unused argument, here to maintain method signature compatibility.
	 * @param bool   $forget2 Unused argument, here to maintain method signature compatibility.
	 * @return void
	 */
	public function render($data, $forget1 = null, $forget2 = false) {
		$this->beforeRender($data);
		$output = json_encode($data);
		$this->afterRender($output);

		if (!trim($output) && $this->responseStatusCode == HTTP_OK) {
			$this->responseStatusCode = HTTP_NO_CONTENT;
		}

		$this->setStatusCode();
		echo $output;
		\Yii::app()->end();
	}

} 