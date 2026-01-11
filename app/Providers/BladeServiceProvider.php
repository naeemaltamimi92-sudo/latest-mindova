<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;
use App\Services\TranslationService;

/**
 * Blade Service Provider
 *
 * Registers custom Blade directives for enhanced translation functionality.
 *
 * @package App\Providers
 * @author Mindova Team
 * @version 1.0.0
 */
class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslationDirectives();
        $this->registerLocaleDirectives();
        $this->registerRtlDirectives();
    }

    /**
     * Register translation directives
     *
     * @return void
     */
    private function registerTranslationDirectives(): void
    {
        /**
         * @trans directive - Cleaner alternative to __()
         *
         * Usage: @trans('welcome.message')
         */
        Blade::directive('trans', function ($expression) {
            return "<?php echo __({$expression}); ?>";
        });

        /**
         * @transChoice directive - Pluralization support
         *
         * Usage: @transChoice('messages.count', $count)
         */
        Blade::directive('transChoice', function ($expression) {
            return "<?php echo trans_choice({$expression}); ?>";
        });

        /**
         * @transAttr directive - For HTML attributes (no echo)
         *
         * Usage: <input placeholder="@transAttr('form.email')">
         */
        Blade::directive('transAttr', function ($expression) {
            return "<?php echo e(__({$expression})); ?>";
        });

        /**
         * @transRaw directive - Unescaped translation (use with caution)
         *
         * Usage: @transRaw('content.html')
         */
        Blade::directive('transRaw', function ($expression) {
            return "<?php echo __({$expression}); ?>";
        });
    }

    /**
     * Register locale-specific directives
     *
     * @return void
     */
    private function registerLocaleDirectives(): void
    {
        /**
         * @locale directive - Conditional locale block
         *
         * Usage:
         * @locale('ar')
         *     <p>Arabic content</p>
         * @endlocale
         */
        Blade::directive('locale', function ($expression) {
            return "<?php if (App::getLocale() == {$expression}): ?>";
        });

        Blade::directive('endlocale', function () {
            return "<?php endif; ?>";
        });

        /**
         * @notlocale directive - Inverse locale check
         *
         * Usage:
         * @notlocale('en')
         *     <p>Non-English content</p>
         * @endnotlocale
         */
        Blade::directive('notlocale', function ($expression) {
            return "<?php if (App::getLocale() != {$expression}): ?>";
        });

        Blade::directive('endnotlocale', function () {
            return "<?php endif; ?>";
        });

        /**
         * @locales directive - Check if locale is in list
         *
         * Usage:
         * @locales(['ar', 'he'])
         *     <p>RTL content</p>
         * @endlocales
         */
        Blade::directive('locales', function ($expression) {
            return "<?php if (in_array(App::getLocale(), {$expression})): ?>";
        });

        Blade::directive('endlocales', function () {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register RTL/LTR directives
     *
     * @return void
     */
    private function registerRtlDirectives(): void
    {
        /**
         * @rtl directive - RTL-only content
         *
         * Usage:
         * @rtl
         *     <p>RTL content</p>
         * @endrtl
         */
        Blade::directive('rtl', function () {
            return "<?php if (in_array(App::getLocale(), ['ar', 'he', 'ur', 'fa'])): ?>";
        });

        Blade::directive('endrtl', function () {
            return "<?php endif; ?>";
        });

        /**
         * @ltr directive - LTR-only content
         *
         * Usage:
         * @ltr
         *     <p>LTR content</p>
         * @endltr
         */
        Blade::directive('ltr', function () {
            return "<?php if (!in_array(App::getLocale(), ['ar', 'he', 'ur', 'fa'])): ?>";
        });

        Blade::directive('endltr', function () {
            return "<?php endif; ?>";
        });

        /**
         * @dir directive - Output direction attribute
         *
         * Usage: <html @dir>
         * Output: <html dir="rtl"> or <html dir="ltr">
         */
        Blade::directive('dir', function () {
            return "<?php echo 'dir=\"' . (in_array(App::getLocale(), ['ar', 'he', 'ur', 'fa']) ? 'rtl' : 'ltr') . '\"'; ?>";
        });

        /**
         * @direction directive - Get current direction
         *
         * Usage: {{ @direction }}
         */
        Blade::directive('direction', function () {
            return "<?php echo in_array(App::getLocale(), ['ar', 'he', 'ur', 'fa']) ? 'rtl' : 'ltr'; ?>";
        });
    }
}
