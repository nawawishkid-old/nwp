<?php

namespace NWP\Facade;

use \NWP\RenderableInterface;
use \NWP\RendererTrait;
use \NWP\AbstractEventCollector;

class AdminSettingsForm extends AbstractEventCollector implements RenderableInterface
{
	use RendererTrait;

	const FUNCTION_SETTINGS_FIELDS = 'settings_fields';

	const FUNCTION_DO_SETTINGS_SECTIONS = 'do_settings_sections';

	const FUNCTION_SUBMIT_BUTTON = 'submit_button';

	const HTTP_POST_METHOD = 'post';

	const HTTP_GET_METHOD = 'get';

	private $method = 'post';

	private $url = 'options.php';

	private $info = [
		'pageId' => null
	];

	private $attributes = [];

	private $pageId = null;

	/**
	 * @property array Array of menu
	 */
	private $sections = [];

	public function __construct()
	{

	}

	public function register() : void
	{
		foreach ($this->sections as $section) {
			$section->addEventCollector($this->eventCollector);
			$section->register();
		}
	}

	public function render()
	{
?>
	<form<?php echo $this->composeAttributes(); ?>>
		<?php
		$this->renderSecurityFields();
		$this->renderUserFields();
		$this->renderSubmitButton();
		?>
	</form>
<?php
	}

	public function composeAttributes()
	{
		$attributes = '';

		foreach ($this->attributes as $name => $value) {
			$attributes .= " $name=\"$value\"";
		}

		return $attributes;
	}

	public function method(string $method)
	{
		$lowerCaseMethod = mb_strtolower($method);

		if ($lowerCaseMethod !== self::HTTP_GET_METHOD && $lowerCaseMethod !== self::HTTP_POST_METHOD) {
			throw new InvalidArgumentException(
				sprintf(
					"HTTP Method for HTML Form element must be either '%s' or '%s', '%s' given.",
					self::HTTP_POST_METHOD,
					self::HTTP_GET_METHOD,
					$method
				)
			);
		}

		$this->attributes['method'] = $method;

		return $this;
	}

	/**
	 * Must support page string slug
	 */
	public function for(AdminPage $page)
	{
		$this->pageId = $page->id;

		return $this;
	}

	public function addSections(...$sections)
	{
		foreach ($sections as $section) {
			$this->addSection($section);
		}

		return $this;
	}

	public function addSection(AdminSettingsSection $section)
	{
		$this->sections[] = $section;

		return $this;
	}

	public function url(string $url)
	{
		$this->attributes['action'] = $url;

		return $this;
	}

	public function className(string $className)
	{
		$this->attributes['class'] = $className;

		return $this;
	}

	public function id(string $id)
	{
		$this->attributes['id'] = $id;

		return $this;
	}

	public function renderSecurityFields()
	{
		call_user_func(self::FUNCTION_SETTINGS_FIELDS, $this->pageId);
	}

	public function renderUserFields()
	{
		call_user_func(self::FUNCTION_DO_SETTINGS_SECTIONS, $this->pageId);
	}

	public function renderSubmitButton()
	{
		call_user_func(self::FUNCTION_SUBMIT_BUTTON);
	}
}
