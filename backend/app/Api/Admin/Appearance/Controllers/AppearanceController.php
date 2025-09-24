<?php namespace Api\Admin\Appearance\Controllers;

use Api\Admin\Appearance\AppearanceSaver;
use Api\Admin\Appearance\AppearanceValues;
use Api\Settings\Settings;
use Illuminate\Http\Request;
use Api\Core\BaseController;

class AppearanceController extends BaseController {

    /**
     * @var Request
     */
    private $request;

    /**
     * @var AppearanceValues
     */
    private $values;

    /**
     * @var AppearanceSaver
     */
    private $saver;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param Request $request
     * @param AppearanceValues $values
     * @param AppearanceSaver $saver
     * @param Settings $settings
     */
    public function __construct(
        Request $request,
        AppearanceValues $values,
        AppearanceSaver $saver,
        Settings $settings
    )
	{
        $this->saver = $saver;
        $this->values = $values;
        $this->request = $request;
        $this->settings = $settings;
    }

    /**
     * Save user modifications to site appearance.
     */
    public function save()
    {
        $this->authorize('update', 'AppearancePolicy');

        $this->saver->save($this->request->all());
        return $this->success();
	}

    /**
     * Get user defined and default values for appearance editor.
     *
     * @return array
     */
    public function getValues()
    {
        $this->authorize('update', 'AppearancePolicy');

        return $this->values->get();
    }
}

