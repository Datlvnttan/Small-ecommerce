<?php

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Config;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Product';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'product';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\BrandRepositoryInterface::class, \Modules\Product\Repositories\Repository\BrandRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\ProductRepositoryInterface::class, \Modules\Product\Repositories\Repository\ProductRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\ProductImageRepositoryInterface::class, \Modules\Product\Repositories\Repository\ProductImageRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\ProductAttributeRepositoryInterface::class, \Modules\Product\Repositories\Repository\ProductAttributeRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\ProductAttributeOptionRepositoryInterface::class, \Modules\Product\Repositories\Repository\ProductAttributeOptionRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\SkuProductAttributeOptionRepositoryInterface::class, \Modules\Product\Repositories\Repository\SkuProductAttributeOptionRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\ProductFlashSaleRepositoryInterface::class, \Modules\Product\Repositories\Repository\ProductFlashSaleRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\FlashSaleRepositoryInterface::class, \Modules\Product\Repositories\Repository\FlashSaleRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\CategoryRepositoryInterface::class, \Modules\Product\Repositories\Repository\CategoryRepository::class);
        $this->app->bind(\Modules\Product\Repositories\Interface\SkuRepositoryInterface ::class,\Modules\Product\Repositories\Repository\SkuRepository::class
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
