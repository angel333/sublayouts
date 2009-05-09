<?php

class SubLayoutsView extends View
{
	public function parseSubLayoutsPath ($path)
	{
		if (empty($path))
			return array();

		if (!strstr($path, '>'))
			$names = array ($path);
		else
		{
			$names = explode('>', $path);
			$names = array_reverse($names);
		}

		foreach ($names as $key => $val)
			if (substr($val, 0, 1) == '~')
				$names[$key] = "../{$this->viewPath}/layouts/" . substr($val, 1);

		return $names;
	}


	public function render ($action = null, $layout = null, $file = null)
	{
		if ($file != null)
			$action = $file;

		if ($layout === null)
			$layout = $this->layout;

		$out = null;

		$paths = $this->parseSubLayoutsPath($action);
		$action = array_shift($paths);

		$out = parent::render($action, false);

		foreach ($paths as $path)
			$out = $this->renderLayout($out, $path);

		$out = $this->renderLayout($out, $layout);

		return $out;
	}


	public function renderLayout ($content_for_layout, $layout = null)
	{
		$paths = $this->parseSubLayoutsPath($layout);

		foreach ($paths as $path)
			$content_for_layout = parent::renderLayout($content_for_layout, $path);

		return $content_for_layout;
	}
}
