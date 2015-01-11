// Gulp includes
var gulp   = require('gulp');
var config = require('./gulp-config.json');

// Plugins includes
var jshint = require('gulp-jshint');
var less = require('gulp-less');
var concat = require('gulp-concat');
var minifycss = require('gulp-minify-css');
var browserSync = require('browser-sync');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var gutil = require('gulp-util');

// BROWSER: Proxy sync
gulp.task('browser-sync', function() {
    browserSync({
        proxy: config.url
    });
});

// BROWSER: Reload
gulp.task('bs-reload', function() {
    browserSync.reload();
});

// FONTS: Copy fonts dir
gulp.task('fonts', function() {
    return gulp.src(['bower_components/bootstrap/fonts/*', 'bower_components/font-awesome/fonts/*'])
        .pipe(gulp.dest('web/fonts'));
});

// CSS: Compile & minify Less
gulp.task('less', function() {
    return gulp.src(['app/Resources/public/css/**/*.less'])
        .pipe(concat('main.css'))
        .pipe(less({ sourceMap: true,  paths: ['./bower_components']})).on('error', gutil.log)
        .pipe(minifycss())
        .pipe(gulp.dest('web/css'));
});

// JS: Lint
gulp.task('lint', function() {
    return gulp.src('app/Resources/public/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// JS: Concatenate & minify vendor scripts
gulp.task('scripts', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/lodash/dist/lodash.js',
            'bower_components/numeral/numeral.js',
            'bower_components/numeral/languages/es.js',
            'bower_components/modernizr/modernizr.js',
            'bower_components/holderjs/holder.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
            'bower_components/angular/angular.js',
            'bower_components/angular-resource/angular-resource.js',
            'bower_components/angular-cookies/angular-cookies.js',
            'bower_components/angular-sanitize/angular-sanitize.js',
            'bower_components/angular-route/angular-route.js',
            'bower_components/angular-touch/angular-touch.js',
            'bower_components/angular-truncate/src/truncate.js',
            'bower_components/checklist-model/checklist-model.js',
            'bower_components/angular-google-maps/dist/angular-google-maps.js'])
        .pipe(concat('main.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// JS: Concatenate & minify custom scripts
gulp.task('myjs', function() {
    return gulp.src('app/Resources/public/js/**/*.js')
        .pipe(concat('my.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('my.min.js'));
});

// Watch with Browser Sync
gulp.task('bswatch', ['browser-sync'], function() {
    gulp.watch('app/Resources/views/Front/**/*.twig', ['bs-reload']);
    gulp.watch('app/Resources/public/js/**/*.js', ['lint', 'myjs', 'bs-reload']);
    gulp.watch('app/Resources/public/css/**/*.less', ['less', 'bs-reload']);
});

// Watch
gulp.task('watch', function() {
    gulp.watch('app/Resources/public/js/**/*.js', ['lint', 'myjs']);
    gulp.watch('app/Resources/public/css/**/*.less', ['less']);
});

// Default Task
gulp.task('default', ['lint', 'fonts', 'less', 'scripts', 'myjs']);
