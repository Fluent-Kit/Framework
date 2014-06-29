<?php 
namespace FluentKit\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return Container
     */
    protected function createFluentKitDriver()
    {
        return new Container($this->app);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return 'fluentkit';
    }

    /**
     * Detect available themes.
     *
     * @return array
     */
    public function detect()
    {
        return $this->app['fluentkit.theme.finder']->detect();
    }
}