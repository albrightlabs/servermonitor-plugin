<?php namespace Albrightlabs\ServerMonitor\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Servers Backend Controller
 */
class Servers extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Albrightlabs.ServerMonitor', 'servers');
    }

    /**
     * Inject row class based on record status.
     *
     * @param $lesson
     * @param $definition
     * @return string|void
     */
    public function listInjectRowClass($server, $definition = null)
    {
        // red for bad
        if (in_array($server->status, [500, 404])) {
            return 'negative';
        }
        // green for good
        elseif ($server->status == 200) {
            return 'positive';
        }
    }
}
