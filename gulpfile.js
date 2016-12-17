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

// Build
gulp.task('build', [
    'bootstrap',
    'bootstrap-switch',
    'layout',
    'font-awesome',
    'components',
    'colors'
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

