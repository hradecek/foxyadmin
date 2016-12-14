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
var pluginsDir = './public/global/plugins';

// Bootstrap
var bootstrap = {
    src: './node_modules/bootstrap-sass/assets/stylesheets',
    dst: pluginsDir + '/bootstrap'
};

gulp.task('bootstrap', function() {
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

gulp.task('font-awesome', function() {
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

// Build
gulp.task('build', ['bootstrap-switch', 'bootstrap'], function () {
});

// Clean all generated files
gulp.task('clean', function() {
    del('./public/global')
});
