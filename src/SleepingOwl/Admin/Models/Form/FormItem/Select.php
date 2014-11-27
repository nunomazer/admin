<?php namespace SleepingOwl\Admin\Models\Form\FormItem;

use SleepingOwl\Admin\Exceptions\MethodNotFoundException;
use SleepingOwl\Support\Arr;

/**
 * Class Select
 * @package SleepingOwl\Admin\Models\Form\FormItem
 * @method Select list($modelClass)
 */
class Select extends BaseFormItem
{
	protected $list;

	public function render()
	{
		if (is_array($this->list))
		{
			$list = $this->list;
			$list = array_combine($list, $list);
		} else
		{
			if ( ! method_exists($this->list, 'getList'))
			{
				throw new \Exception('You must implement "public static function getList()" in "' . $this->list . '"');
			}
			$list = forward_static_call([
				$this->list,
				'getList'
			]);
		}
		return $this->formBuilder->selectGroup($this->name, $this->label, $list, $this->getValueFromForm(), [
			'class' => 'multiselect',
			'size'  => 2
		]);
	}

	public function enum($values)
	{
		$this->list(array_combine($values, $values));
	}

	function __call($name, $arguments)
	{
		if ($name === 'list')
		{
			$this->list = Arr::get($arguments, 0, null);
			return $this;
		}
		throw new MethodNotFoundException(get_class($this), $name);
	}


} 