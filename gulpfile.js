/**
 * File:   gulpfile.js
 * Author: Ivo Hradek <ivohradek@gmail.com>
 */
'use strict';

// Gulp imports
var del = require('del'),
    gulp = require('gulp'),
    sassc = require('gulp-sass'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    prettify = require('gulp-prettify'),
    minifyCss = require('gulp-clean-css');

// Global variables
var layoutDir = './public/layout',
    themesDir = './public/global/themes',
    pluginsDir = './public/global/plugins';

// Bootstrap
var bootstrap = {
    src: './node_modules/bootstrap-sass/assets/stylesheets',
    dst: pluginsDir + '/bootstrap'
};

gulp.task('bootstrap', function () {
    // Rename _bootstrap.scss to bootstrap.scss
    gulp.src(bootstrap.src + '/_bootstrap.scss')
        .pipe(rename(bootstrap.src + '/bootstrap.scss'))
        .pipe(gulp.dest('.'));

    // Compile & minify
    gulp.src(bootstrap.src + '/bootstrap.scss')
        .pipe(sassc())
        .pipe(gulp.dest(bootstrap.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(bootstrap.dst + '/css'));

    // Copy JS
    gulp.src('./node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js')
        .pipe(gulp.dest(bootstrap.dst + '/js'));
});

// Font-awesome
var fontAwesome = {
    src: './node_modules/font-awesome/scss',
    dst: pluginsDir + '/font-awesome'
};

gulp.task('font-awesome', function () {
    // Compile & minify
    gulp.src(fontAwesome.src + '/font-awesome.scss')
        .pipe(sassc())
        .pipe(gulp.dest(fontAwesome.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(fontAwesome.dst + '/css'));
    // Copy fonts
    gulp.src('./node_modules/font-awesome/fonts/**/*')
        .pipe(gulp.dest(fontAwesome.dst + '/fonts'));
});

// Bootstrap-switch
var bootstrapSwitch = {
    src: './node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css',
    dst: pluginsDir + '/bootstrap-switch'
};

gulp.task('bootstrap-switch', function () {
    // Copy
    gulp.src(bootstrapSwitch.src)
        .pipe(gulp.dest(bootstrapSwitch.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(bootstrapSwitch.dst + '/css'));
});

// Bootstrap Wizard
var bootstrapWizard = {
    src: './node_modules/twitter-bootstrap-wizard',
    dst: pluginsDir + '/bootstrap-wizard/js'
};

gulp.task('bootstrap-wizard', function () {
    gulp.src(bootstrapWizard.src + '/jquery.bootstrap.wizard.min.js')
        .pipe(gulp.dest(bootstrapWizard.dst));
});

// Plugins
var plugins = {
    src: './resources/assets/sass/global',
    dst: pluginsDir
};

gulp.task('plugins', function () {
    gulp.src(plugins.src + '/plugins.scss')
        .pipe(sassc())
        .pipe(gulp.dest(plugins.dst))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(plugins.dst));
});

// Layout
var layout = {
    src: './resources/assets/sass/layout',
    dst: layoutDir
};

gulp.task('layout', function () {
    gulp.src(layout.src + '/layout.scss')
        .pipe(sassc())
        .pipe(gulp.dest(layout.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(layout.dst + '/css'));
});

// Components
var components = {
    src: './resources/assets/sass/global',
    dst: themesDir
};

gulp.task('components', function () {
    gulp.src(components.src + '/components.scss')
        .pipe(sassc())
        .pipe(gulp.dest(components.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(components.dst + '/css'));
});

gulp.task('colors', function () {
    gulp.src('./resources/assets/sass/themes/*.scss')
        .pipe(sassc())
        .pipe(gulp.dest(components.dst + '/css'))
        .pipe(minifyCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(components.dst + '/css'));
});

// JQuery
var jquery = {
    src: './node_modules/jquery/dist',
    dst: pluginsDir + '/jquery/js'
};

var jqueryValidation = {
    src: './node_modules/jquery-validation',
    dst: pluginsDir + '/jquery-validation/js'
};

var jqueryCookie = {
    src: './node_modules/jquery.cookie',
    dst: pluginsDir + '/jquery-cookie/js'
};

gulp.task('jquery', function () {
    gulp.src(jquery.src + '/jquery.min.js')
        .pipe(gulp.dest(jquery.dst));
    gulp.src(jqueryValidation.src + '/dist/jquery.validate.min.js')
        .pipe(gulp.dest(jqueryValidation.dst));
    gulp.src(jqueryValidation.src + '/dist/additional-methods.js')
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(jqueryValidation.dst));
    gulp.src(jqueryCookie.src + '/jquery.cookie.js')
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(jqueryCookie.dst));
});

// App scripts
gulp.task('scripts', function () {
    gulp.src('./resources/assets/scripts/*.js')
        .pipe(gulp.dest('./public/scripts'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./public/scripts'));
});

// Build
gulp.task('build', [
    'bootstrap',
    'bootstrap-switch',
    'layout',
    'font-awesome',
    'components',
    'colors',
    'jquery',
    'bootstrap-wizard',
    'plugins',
    'scripts'
    ], function () {}
);

// Watch
gulp.task('watch', function () {
    gulp.watch('./resources/assets/sass/**/*.scss', ['build']);
});

// Clean all generated files
gulp.task('clean', function () {
    del('./public/global');
    del('./public/layout');
});
