<?php namespace Albrightlabs\ServerMonitor;

use Backend;
use Albrightlabs\ServerMonitor\Models\Server;
use System\Classes\PluginBase;

/**
 * ServerMonitor Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Server Monitor',
            'description' => 'Provides the ability to monitor multiple online resources.',
            'author'      => 'Albright Labs LLC',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'albrightlabs.servermonitor.manage_endpoints' => [
                'tab' => 'Server Monitor',
                'label' => 'View and manage server monitor endpoints.'
            ],
        ];
    }

    /**
     * Registers the settings for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Server Monitor',
                'description' => 'Manage server monitor endpoints.',
                'category'    => 'Server Monitor',
                'icon'        => 'icon-eye',
                'url'         => Backend::url('albrightlabs/servermonitor/servers'),
                'order'       => 500,
                'keywords'    => 'auto migrate database',
                'permissions' => ['albrightlabs.automigrate.manage_settings'],
            ]
        ];
    }

    /**
     * Registers the scheduled functions for this plugin.
     *
     * @return void
     */
    public function registerSchedule($schedule)
    {
        $schedule->call(function () {

            if($servers = Server::all()){
                foreach($servers as $server){
                    if(null != $server->endpoint){
                        if($status = \Albrightlabs\ServerMonitor\Plugin::pingDomain($server->id)){

                            // save status
                            $server->status = $status;
                            $server->save();
                        }
                    }
                }
            }

        })->everyMinute();
    }

    /**
     * Pings an IP to see if there's a response.
     *
     * @param $server
     * @return false|float|int
     */
    public static function pingDomain($server){
        if($server = Server::find($server)){
            $domain = $server->endpoint;
            $domain = str_replace('https://', 'http://', $domain);

            $ch = curl_init($domain);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!$retcode) {
                return -1;
            }
            else {
                return $retcode;
            }

        }

        return -1;
    }
}
