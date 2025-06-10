let mix = require('laravel-mix');
const purgeCss = require('@fullhuman/postcss-purgecss');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/themes/' + directory;
const dist = 'public/themes/' + directory;

mix
    .sass(
        source + '/assets/sass/style.scss',
        dist + '/css',
        {},
        [
            purgeCss({
                content: [
                    source + '/assets/js/components/*.vue',
                    source + '/layouts/*.blade.php',
                    source + '/partials/*.blade.php',
                    source + '/partials/**/*.blade.php',
                    source + '/views/*.blade.php',
                    source + '/views/**/*.blade.php',
                    source + '/views/**/**/*.blade.php',
                    source + '/views/**/**/**/*.blade.php',
                    source + '/widgets/**/templates/frontend.blade.php',
                ],
                defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
                safelist: [
                    /dd-trigger/,
                    /menu-on/,
                    /^offcanvas-/,
                    /show-offcanvas/,
                    /^nice-/,
                    /^slick-/,
                    /show-admin-bar/,
                    /fade/,
                    /active/,
                    /show/
                ],
            })
        ])

    .js(source + '/assets/js/main.js', dist + '/js')
    .js(source + '/assets/js/review.js', dist + '/js')

if (mix.inProduction()) {
    mix
        .copy(dist + '/css/style.css', source + '/public/css')
        .copy(dist + '/js/main.js', source + '/public/js')
        .copy(dist + '/js/review.js', source + '/public/js')
}
